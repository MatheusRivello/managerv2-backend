<?php

namespace App\Services\Integracao;

use App\Services\BaseService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\RequestInterface;
use Rollbar\Rollbar;
use stdClass;

abstract class IntegracaoService implements IIntegracao {
    use Traits\Log;
    use Traits\Database;
    use Traits\Config;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $ModelClass;
    protected $method;
    protected $url;
    protected $path;
    /**
     * @var function
     * @param Model $model
     * @param stdCLass $obj
     * @throws \Exception
     * @return Model
     */
    protected $updateFields;
    protected $limit;
    protected $batch;
    protected $offset;
    protected $where;
    protected $counter;
    protected $tenant;
    protected $data;
    protected $requestParams;
    protected $modelName;
    private $token;
    private $client;
    private $prepared;
    private $connection;
    private $tableAction;
    private $requestTime = 0;
    private $processingAndTransactionTime = 0;
    private const TRUNCATE = 1;
    private const INACTIVATE = 2;
    private const NOTHING = 0;
    
    public function __construct($params) {
        $this->counter = 0;
        $this->url = URL_INTEGRADOR;
        $this->method = 'GET';
        $this->offset = 0;
        $this->token = Login::getInstance()->getToken();
        $this->data = [];
        $this->modelName = $this->getClassName();
        $this->tableAction = $this->getTableAction();
        
        $tenant = $params['tenant'];
        $this->tenant = $tenant;
        $this->client = new Client([
            'timeout' => intval($this->getConfigIntegrador('api_timeout', $tenant) ?? 120),
        ]);
        $this->connection = $this->getConnection($tenant);
        $this->limit = intval($this->getConfigIntegrador('limit', $tenant));
        $this->batch = intval($this->getConfigIntegrador('insert_em_lotes_de', $tenant));
        DB::setDefaultConnection("empresa" . $tenant);
    }

    public function request() {
        try {
            $this->prepareTable();
            do {
                $res = $this->requestAPI();
                if ($res->getStatusCode() == 200) {
                    $this->data = json_decode($res->getBody())->data;
                    $count = count($this->data);
                    if ($count == 0) {
                        $this->writeLog("[Pacote " . (intdiv($this->offset, $this->limit)) . "] => Sem registros para salvar!");
                        break;
                    }
                    $this->insertInDB();
                    $this->offset += $count;
                }
            } while ($count == $this->limit);
        }
        catch (\GuzzleHttp\Exception\RequestException $th)
        {
            if ($th->getCode() == 401)
            {
                $this->token = Login::getInstance()->request()->getToken();
                $this->request();
            }
            else if ($th->getCode() < 500)
            {
                $this->writeLog(
                    "Erro com status " . $th->getCode() . ". " . $th->getMessage() .
                    ($th->getResponse() ? $th->getResponse()->getBody()->__toString() : "")
                );
                Rollbar::error(
                    "API Firebird: Request status ".$th->getCode(),
                    [
                        "tenant" => $this->tenant,
                        "endpoint" => $this->path,
                        "exception" => $th,
                        "trace" => $th->getTraceAsString(),
                    ]
                );
            }
            else
            {
                $this->writeLog(
                    "Erro com status " . $th->getCode() . ". " .
                    ($th->getResponse() ? $th->getResponse()->getBody()->__toString() . ". " : "") .
                    "Este erro ocorreu ao fazer a seguinte requisição na API do Firebird: " . $this->handleRequestInterface($th->getRequest())
                );
                Rollbar::error(
                    "API Firebird: Request status ".$th->getCode(),
                    [
                        "tenant" => $this->tenant,
                        "endpoint" => $this->path,
                        "exception" => $th,
                        "trace" => $th->getTraceAsString(),
                    ]
                );
            }
            $this->offset += $this->limit;
        }
        $this->writeLog("Requisição na API Firebird: ".$this->requestTime."s, "."processamento dos dados e transações: ".$this->processingAndTransactionTime."s.");

        Rollbar::info("[INFO] Métricas de desempenho da sinc", [
            "tenant" => $this->tenant,
            "endpoint" => $this->path,
            "time" => [
                "firebirdAPI" => $this->requestTime,
                "processingAndTransactionTime" => $this->processingAndTransactionTime
            ]
        ]);

        return $this;
    }

    public function getLog() {
        return $this->readLog();
    }

    private function requestAPI() {
        $start = microtime(true);

        $query = array_merge([
            'Offset' => $this->offset,
            'Limit' => $this->limit,
            ],
            empty($this->requestParams) ? [] : $this->requestParams
        );
        $response = $this->client->request($this->method, $this->url . $this->path, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
                'tenant' => $this->tenant
            ],
            'query' => $query,
            'verify' => false,
            'stream' => true
        ]);
        $end = microtime(true);
        $this->requestTime += number_format($end - $start, 3);

        return $response;
    }

    private function handleRequestInterface(RequestInterface $req) {
        $request = [
            'url' => $req->getUri(),
            'method' => $req->getMethod(),
            'headers' => $req->getHeaders(),
            'query' => $req->getBody(),

        ];
        return "headers" . json_encode($request);
    }

    private function upsert() {
        $data = $this->data;
        $updateFields = $this->updateFields;
        $where = $this->where;
        $saved = 0;
        $notSalved = 0;
        $values = [];
        $fieldsMapped = [];
        foreach ($data as $key => $obj) {
            try
            {
                $clazz = new stdClass();

                $fieldsMapped = (array) $updateFields($clazz, $obj);

                array_push($values, $fieldsMapped);

                $this->counter++;
            }
            catch (\Throwable $th)
            {
                $this->addLog("Erro em " . $this->modelName . " com status " . $th->getCode() . ". " . $th->getMessage(), $th);
                $notSalved++;
            }
        }
        $this->saveLog("erros-detalhados/" . date("H\h"));

        $chunks = array_chunk($values, $this->batch);

        foreach ($data as $obj)
        {
            try {
                $uniqueBy = array_keys($where($obj));
                // exit after first object that not throw exception
                break;
            } catch (\Throwable $th) {
                // throw $th;
            }
        }

        foreach ($chunks as $chunk)
        {
            $this->ModelClass::upsert(
                $chunk,
                $uniqueBy,
                array_keys($fieldsMapped),
            );
        }

        return [
            'saved' => count($values),
            'notSalved' => $notSalved
        ];
    }

    private function bulkInsertAfterTruncate() {
        $data = $this->data;
        $updateFields = $this->updateFields;
        $clousure = $this->where;
        $saved = 0;
        $notSalved = 0;
        try
        {
            $insertData = [];
            // object to associative array for use in insertOrIgnore method
            foreach ($data as $obj) {
                $model = new stdClass();
                try {
                    $insertData[] = (array) $updateFields($model, $obj);
                } catch (\Throwable $th) {
                    if ($this->isUndefinedIndexOrOffset($th)) {
                        $this->addLog("Tabela referenciada por " . $this->modelName . " não possui o registro com este id_filial-id_retaguarda. " . $th->getMessage(), $th);
                    }
                    else {
                        $this->addLog("Erro em " . $this->modelName . " com status " . $th->getCode() . ". " . $th->getMessage(), $th);
                    }
                    $notSalved++;
                }
            }
            $this->saveLog("erros-detalhados/" . date("H\h"));

            // split array into chunks for insert in batches
            $chunks = array_chunk($insertData, $this->batch);

            $count = $this->ModelClass::count();

            foreach ($chunks as $key => $chunk) {
                $this->ModelClass::insertOrIgnore($chunk);
            }
            $countAfterInsert = $this->ModelClass::count();

            $saved = $countAfterInsert - $count;
            $notSalved += count($insertData) - $saved;
        }
        catch (\Throwable $th)
        {
            $this->writeLog("Erro em " . $this->modelName . " com status " . $th->getCode() . ". " . $th->getMessage() . "\n" . $th->getTraceAsString(), "erros-detalhados/" . date("H\h"));
            $notSalved++;
            Rollbar::error("Exception em bulkInsertAfterTruncate", [
                "tenant" => $this->tenant,
                "exception" => $th,
            ]);
        }
        return [
            'saved' => min($saved, $this->limit),
            'notSalved' => max($notSalved, 0)
        ];
    }

    private function isUndefinedIndexOrOffset(\Throwable $th)
    {
        return strstr($th->getMessage(), 'Undefined index') || strstr($th->getMessage(), 'Undefined offset');
    }

    protected function insertInDB() {
        $start = microtime(true);

        $this->getDataFromReferencedTables();

        if ($this->tableAction === self::TRUNCATE)
        {
            $info = $this->bulkInsertAfterTruncate();
        }
        else {
            $info = $this->upsert();
        }
        $this->writeLog("[Pacote " . (intdiv($this->offset, $this->limit)) . "] => " . $info['saved'] . " salvo(s), " . $info['notSalved'] . " não salvo(s).");

        $end = microtime(true);
        $this->processingAndTransactionTime += number_format($end - $start, 3);
    }

    private function tableWasTruncated() {
        return $this->tableAction == self::TRUNCATE;
    }

    private function encodeAndReplace($obj) {
        return str_replace(["{", "}", "\""], '', json_encode($obj));
    }

    private function prepareTable() {
        if ($this->prepared) return;
        if ($this->tableAction == self::TRUNCATE) {
            $this->truncate();
            $this->writeLog("Tabela " . $this->modelName . " truncada.");
        } else {
            if($this->tableAction == self::INACTIVATE) {
                $rows = $this->ModelClass::where([])->update(['status' => 0]);
                $this->writeLog($rows . " registro(s) inativado(s).");
            }
        }
        $this->prepared = true;
    }

    private function getTableAction() {
        if (array_column($this->getTablesThatDataCanBeExcluded(), $this->modelName)) return self::TRUNCATE;
        if (array_column($this->getTablesThatDataCanBeInactivated(), $this->modelName)) return self::INACTIVATE;
        return self::NOTHING;
    }

    private function truncate() {
        $this->connection->statement("SET FOREIGN_KEY_CHECKS=0");
        $this->ModelClass::truncate();
        $this->connection->statement("SET FOREIGN_KEY_CHECKS=1");
    }

    private function getConnection($tenant) {
        return DB::connection(
            (new BaseService())->connectionTenant($tenant)
        );
    }

    protected function getDataFromReferencedTables() {}
}
