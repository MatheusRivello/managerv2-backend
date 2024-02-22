<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\Info;
use OpenApi\Annotations\Server;

class SwaggerController extends Controller
{
    public function index()
    {
        $swagger = \OpenApi\Generator::scan([
            app_path(),
            base_path('vendor/laravel/framework/src/Illuminate/Foundation'),
        ]);

        return response()->json($swagger);
    }
}
