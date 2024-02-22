<?php

namespace App\Services\Integracao\Traits;

use App\Models\Central\ConfigIntegrador;

trait Config {
    public static function getConfigIntegrador($name, $tenant = null) {
        $collection = ConfigIntegrador::where('name', $name)
            ->where('fk_empresa', $tenant)->get();

        if ($collection->count() > 0) return $collection[0]->value;

        $collection = ConfigIntegrador::where('name', $name)->get();
        if ($collection->count() > 0) return $collection[0]->value;
        return null;
    }
}