<?php

namespace App\Http\Controllers\servico\v1;

use App\Http\Controllers\Controller;
use App\Services\BaseService;
use App\Services\Util\ConvertService;
use App\Services\Util\ErpService;
use Exception;
use Illuminate\Http\Request;

/**
 * @property ConvertService $util
 * @property BaseService $service
 * @property int $idEmpresa
 * @property string $caminho
 * @property string $caminhoTemp
 * @property string $caminhoDefault
 * @property ErpService $erp_service
 * @property int $acaoTabela
 * @property \Illuminate\Database\Eloquent\Model $entity
 * @property Array $firstOrNew
 * @property Array $fields
 */
abstract class BaseServicoController extends Controller
{
    protected $metodo;
    protected $nomeCamposDb;
    protected $tabela;
    public function __construct($metodo, $nomeCamposDb, $tabela, $service = null)
    {
        $this->util = new ConvertService;
        $this->metodo = $metodo;
        $this->nomeCamposDb = $nomeCamposDb;
        $this->tabela = $tabela;

        $this->service = $service instanceof BaseService ? $service : new BaseService();

        $this->idEmpresa = $this->service->usuarioLogado()->fk_empresa;
        $this->caminho = "emp-" . $this->idEmpresa;
        $this->caminhoTemp = $this->caminho . "temp" . DIRECTORY_SEPARATOR;
        $this->caminhoDefault = CAMINHO_PADRAO_STORAGE . $this->caminho;

        $this->erp_service = new ErpService(
            $this->service->usuarioLogado(),
                $this->caminho,
                $this->caminhoTemp,
                $this->caminhoDefault,
            $this->service->connectionTenant($this->idEmpresa),
                $this->nomeCamposDb,
                $this->tabela
        );
    }

    protected function storeUpdate(Request $request)
    {
        try {
            $acaoTabela = isset($this->acaoTabela) ? $this->acaoTabela : NULL;
            return $this->erp_service->zip($this->metodo, $acaoTabela, $request, $this->entity);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    protected function store2($dados)
    {
        try {
            $model = $this->entity::firstOrNew($this->mountFirstOrNewParam($dados)($this->firstOrNew));

            foreach ($this->fields as $key => $field) {
                $model->{$field} = $dados[$field];
            }

            $model->save();
            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    private function mountFirstOrNewParam($request)
    {
        return function ($firstOrNew) use ($request) {
            $arr = [];
            foreach ($firstOrNew as $key => $value) {
                $arr[$value] = $request[$value];
            }
            return $arr;
        };
    }

    protected function store($dados, $model, $firstOrNew, $fields)
    {
        try {
            $model = $model::firstOrNew(self::mountFirstOrNewParam($dados)($firstOrNew));

            foreach ($fields as $key => $field) {
                $model->{$field} = $dados[$field];
            }
            return $model->save();
        } catch (Exception $ex) {
            return ['erro' => true, 'message' => $ex->getMessage()];
        }
    }

    protected function storeVetores($arrayDados, $model, $firstOrNew, $fields)
    {
        try {
            foreach ($arrayDados as $key => $dados) {
                $model = $model::firstOrNew(self::mountFirstOrNewParam($dados)($firstOrNew));

                foreach ($fields as $key => $field) {
                    $model->{$field} = $dados[$field];
                }

                $model->save();
            }
        } catch (Exception $ex) {
            return ['erro' => true, 'message' => $ex->getMessage()];
        }
    }

    protected function inserirVetoresApagaTabela($arrayDados, $model, $serviceController, $acao = 0)
    {
        try {

            $collect = collect($arrayDados);

            $resultado = $this->erp_service->_percorreArrayComTabelasParaPrepararDados($serviceController, $acao);

            if ($resultado) {
                $collect->chunk(LIMIT_INSERSAO_BATCH_1000)
                    ->each(function ($calls) use ($model) {
                        $model::insertMany($calls);
                    });
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return ['erro' => true, 'message' => $ex->getMessage()];
        }
    }
    protected function inserirVetores($arrayDados, $model, $reateLimit = LIMIT_INSERSAO_BATCH_1000, $useDate = false, $campoDtCadastro = NULL, $campoDtAlteracao = NULL)
    {
        try {
            $collect = collect($arrayDados);

            $collect->chunk($reateLimit)
                ->each(function ($calls) use ($model, $useDate, $campoDtCadastro, $campoDtAlteracao) {
                    $model::insertMany($calls, $useDate, $campoDtCadastro, $campoDtAlteracao);
                });
        } catch (Exception $ex) {
            return ['erro' => true, 'message' => $ex->getMessage()];
        }
    }

    private function metodo()
    {
        return $this->metodo;
    }
    protected function updateVetores($arrayDados, $model, $chavePrimaria = null, $camposUpdate, $reateLimit = LIMIT_INSERSAO_BATCH_1000, $useDate = FALSE)
    {
        try {
            $id = isset($chavePrimaria) ? $chavePrimaria : "id";

            $collect = collect($arrayDados);

            $collect->chunk($reateLimit)
                ->each(function ($calls) use ($model, $id, $camposUpdate, $useDate) {
                    $model::updateMany($calls, $id, $camposUpdate, $useDate);
                });
        } catch (Exception $ex) {
            return ['erro' => true, 'message' => $ex->getMessage()];
        }
    }

    protected function _informarMensagemRetorno($totalRegistros, $totalInseridos, $totalAtualizados, $erros)
    {
        $concluidos = $totalInseridos + $totalAtualizados;
        $totalErros = (is_null($erros)) ? 0 : count($erros);

        if ($totalRegistros == 0) {
            $resposta = [
                "code" => 406,
                "status" => "erro",
                "mensagem" => "Nenhum registro RECEBIDO",
            ];
        } else if ($totalErros == 0 && $totalInseridos == $totalRegistros) {
            $resposta = [
                "code" => 200,
                "status" => "sucesso",
                "mensagem" => "Houve somente INSERCAO",
            ];
        } else if ($totalErros == 0 && $totalAtualizados == $totalRegistros) {
            $resposta = [
                "code" => 200,
                "status" => "sucesso",
                "mensagem" => "Houve somente ATUALIZACAO",
            ];
        } else if ($totalErros == $totalRegistros) {
            $resposta = [
                "code" => 406,
                "status" => "erro",
                "mensagem" => "Nenhum registro passou na VALIDACAO",
            ];
        } else if ($totalErros > 0 && $totalInseridos > 0 && $totalAtualizados == 0) {
            $resposta = [
                "code" => 202,
                "status" => "sucesso_parcial",
                "mensagem" => "Somente alguns dados foram INSERIDOS",
            ];
        } else if ($totalErros > 0 && $totalInseridos == 0 && $totalAtualizados > 0) {
            $resposta = [
                "code" => 202,
                "status" => "sucesso_parcial",
                "mensagem" => "Somente alguns dados foram ATUALIZADOS",
            ];
        } else if ($totalErros > 0 && $totalInseridos > 0 && $totalAtualizados > 0) {
            $resposta = [
                "code" => 202,
                "status" => "sucesso_parcial",
                "mensagem" => "Alguns dados foram INSERIDOS e ATUALIZADOS",
            ];
        } else if ($totalErros == 0 && $concluidos == $totalRegistros) {
            $resposta = [
                "code" => 200,
                "status" => "sucesso",
                "mensagem" => "Houve INSERCAO e ATUALIZACAO",
            ];
        } else {
            $resposta = [
                "code" => 404,
                "status" => "erro",
            ];
        }

        $resposta["totalRegistrosEnviados"] = $totalRegistros;
        $resposta["totalRegistrosInseridos"] = $totalInseridos;
        $resposta["totalRegistrosAtualizados"] = $totalAtualizados;
        $resposta["totalErros"] = $totalErros;
        if ($totalErros > 0) {
            $resposta["erros"] = $erros;
        }

        return $resposta;
    }

    protected function _criar_pasta($caminho)
    {
        $this->erp_service->criarPasta($caminho);
    }

    protected function atualizarDadosJSON(Request $request)
    {
        try {
            $arrayDados = json_decode(str_replace("[[", "[", $request->json), true);
            return $this->entity::atualizarDados($arrayDados, NULL);
        } catch (Exception $ex) {
            return $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }
}