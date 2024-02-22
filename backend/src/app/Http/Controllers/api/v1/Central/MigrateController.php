<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Services\Migrate\MigrateDataV1ToV2;
use Illuminate\Http\Request;
use App\Services\Migrate\MigrateService;

class MigrateController extends Controller {

    private $migrateService;
    private $migrateDataService;

    function __construct(MigrateService $migrateService)
    {
        $this->migrateService = $migrateService;
        $this->migrateDataService = new MigrateDataV1ToV2();
    }

    public function tenant(Request $request)
    {
        try {
            return response()->json([
                "saida" => $this->migrateService->tenant($request->tenant)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 400);
        }
    }

    public function migrateDataV1ToV2(Request $request)
    {
        try {
            $this->migrateDataService->setTenant($request->tenant);
            return response()->json([
                "saida" => $this->migrateDataService->migrate()
            ]);
        }
        catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage() . "\n" . $th->getTraceAsString(),
            ], 400);
        }
    }
}