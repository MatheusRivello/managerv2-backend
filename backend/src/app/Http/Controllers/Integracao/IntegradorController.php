<?php

namespace App\Http\Controllers\Integracao;

use App\Http\Controllers\Controller;
use App\Services\Integracao\IntegradorService;
use App\Jobs\IntegradorJob;
use App\Models\Central\PeriodoSincronizacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use stdClass;

class IntegradorController extends Controller
{
    private $integrador;
    private $tenant;

    const ONLINE = 0;
    const PROGRAMADA = 1;

    public function __construct(IntegradorService $integrador)
    {
        $this->integrador = $integrador;
        $user = Auth::guard()->user();
        if (is_null($user)) throw new HttpException(401, "Faça o login para realizar a integração.");
        $this->tenant = $user->fk_empresa;
    }

    // rodar/log
    public function index(Request $request)
    {
        $this->integrador->setRequest($request);
        $this->integrador->setTenant($this->tenant);
        return $this->integrador->sinc();
    }

    // rodar
    public function run(Request $request, stdClass $req)
    {
        $req->ids = $request->ids;
        $req->tipo = $request->tipo;
        if (isset($request->periodo)) $req->periodo = $request->periodo;

        switch ($req->tipo) {
            case self::PROGRAMADA:
                return $this->schedule($req);
            case self::ONLINE:
            default:
                return $this->dispatchJob($req);
        }
    }

    public function log(Request $req) {
        $tenant = $req->tenant ?? $this->tenant;
        $this->integrador->setTenant($tenant);
        return $this->integrador->getLog();
    }

    public function timeline() {
        $this->integrador->setTenant($this->tenant);
        return response()->json($this->integrador->getTimeline());
    }

    private function dispatchJob($req)
    {
        $rowUpdated = PeriodoSincronizacao::where([
            "fk_empresa" => $this->tenant,
            "job_processando" => false
        ])->update(['job_processando' => true]);

        if ($rowUpdated == 0) throw new HttpException(400, "Já existe uma sicronização em andamento.");

        IntegradorJob::dispatch($req, $this->tenant)->onQueue("integracao");
        
        return response()->json([
            "message" => "Sincronização adicionada a fila."
        ]);
    }

    private function schedule($req)
    {
        IntegradorJob::schedule($req, $this->tenant);
        return response()->json([
            "message" => "Sincronização programada salva."
        ]);
    }
}
