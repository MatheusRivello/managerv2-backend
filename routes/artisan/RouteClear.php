<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('config/clear', function () {
    Artisan::call('config:clear');
    return response()->json(["message" => "Config cache cleared"], 200);
});

Route::get('cache/clear', function () {
    Artisan::call('cache:clear');
    return response()->json(["message" => "Cache cleared"], 200);
});

Route::get('rotas/clear', function () {
    Artisan::call('route:clear');
    return response()->json(["message" => "Route cache cleared"], 200);
});
