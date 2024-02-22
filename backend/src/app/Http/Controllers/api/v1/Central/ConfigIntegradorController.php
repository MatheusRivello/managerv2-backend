<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Services\api\ConfigIntegradorService;
use Illuminate\Http\Request;

class ConfigIntegradorController extends Controller
{
    private $configIntegrador;

    public function __construct(ConfigIntegradorService $configIntegrador)
    {
        $this->configIntegrador = $configIntegrador;
    }

    public function getConfigIntegrador()
    {
        return $this->configIntegrador->index();
    }

    public function postConfigIntegrador(Request $request)
    {
        return $this->configIntegrador->post($request);
    }
    public function putConfigIntegrador(Request $request)
    {
        return $this->configIntegrador->put($request);
    }
}
