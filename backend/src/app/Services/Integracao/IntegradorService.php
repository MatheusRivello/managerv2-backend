<?php

namespace App\Services\Integracao;

use App\Models\Central\ConfiguracaoEmpresa;
use App\Models\Central\PeriodoSincronizacao;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Redis;
use Rollbar\Payload\Level;
use Rollbar\Rollbar;

class IntegradorService {
    use Traits\Service;
    use Traits\Log;

    protected $request;
    protected $tenant;
    protected $jobId;

    const ONLINE = 0;

    public function __construct() {}

    public function sinc()
    {
        $this->started();

        foreach ($this->request->ids as $index => $id) {
            $array = $this->getAllServicesName()[$id];
            foreach ($array as $service) {
                $this->writeLog("Sincronizando " . $service . " [" . $id . "]");
                try {
                    $instance = (new Container())->makeWith("App\Services\Integracao\\" . $service, ["params" => ["tenant" => $this->tenant]]);
                    $instance->request();
                } catch (\Throwable $th) {
                    $this->writeLog(
                        "Resposta não esperada. Status " . $th->getCode() . " em $service." . $th->getMessage() . ". " .
                        (env("LOG_TRACEBACK", 0) ? $th->getTraceAsString() : "")
                    );
                }
                $percent = 100 / count($this->request->ids) * $index;

                $this->writeLog("$service sincronizado! " . (int) $percent . "%\n");
            }
        }
        $this->finished();

        return $this->readLog();
    }

    public function getLog() {
        return $this->readLog();
    }

    public function getTimeline() {
        return $this->getTimelineByLog();
    }

    public function setRequest($request) {
        $this->request = $request;
    }

    public function setTenant($tenant) {
        $this->tenant = $tenant;
    }

    public function setJobId($jobId) {
        $this->jobId = $jobId;
    }

    public static function schedule($request, $tenant) {
        self::updateConfiguracaoEmpresa($request, $tenant);
        self::updatePeriodo($request, $tenant);
    }

    private function started() {
        $this->writeLog("Inicio da sincronização! 0%\n");

        self::updateJobId($this->jobId, $this->tenant);

        if($this->request->tipo == self::ONLINE)
        {
            $this->info("Sincronização manual iniciada");
            return;
        }
        else $this->info("Sincronização programada iniciada");

        $rowUpdated = PeriodoSincronizacao::where([
            "fk_empresa" => $this->tenant,
            "job_processando" => false
        ])->update(['job_processando' => true]);

        if ($rowUpdated == 0) throw new \Exception("Sincronização não iniciada, pois uma sincronização já está em andamento.\n");
    }

    private function finished() {
        PeriodoSincronizacao::where(["fk_empresa" => $this->tenant])->update(['job_processando' => false]);

        $this->info("Sincronização finalizada");

        $this->writeLog("Fim da sincronização! 100%\n\n");
    }

    private function info($info)
    {
        Rollbar::log(
            Level::INFO,
            "[INFO] $info.",
            ["empresa" => $this->tenant]
        );
    }

    private static function updatePeriodo($request, $tenant) {
        $periodo = $request->periodo;
        $where = ["fk_empresa" => $tenant];
        $updateFields = [
            "dia" => $periodo["dias"],
            "hora" => $periodo["horarios"]
        ];
        PeriodoSincronizacao::updateOrCreate($where, $updateFields);
    }

    private static function updateJobId($jobId, $tenant) {
        PeriodoSincronizacao::where(["fk_empresa" => $tenant])
            ->update(["job_id" => $jobId]);
    }

    private static function updateConfiguracaoEmpresa($request, $tenant = null) {
        ConfiguracaoEmpresa::where([
            "fk_empresa" => $tenant,
            "tipo" => $request->tipo,
        ])->update(["valor" => 0]);

        ConfiguracaoEmpresa::where([
            "fk_empresa" => $tenant,
            "tipo" => $request->tipo,
        ])
        ->whereIn("fk_tipo_configuracao_empresa", $request->ids)
        ->update(["valor" => 1]);
    }
}