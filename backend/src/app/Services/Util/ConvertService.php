<?php

namespace App\Services\Util;

class ConvertService
{
    public function convertValueJSON($model, $camposPersonalizados = null, $usaDate = NULL)
    {
        $arrayCast = [];
        $camposModel = app($model)->getFillable();

        if (isset($camposPersonalizados)) {
            $camposModel = array_merge($camposModel, $camposPersonalizados);
        }

        foreach ($camposModel as $key => $campo) {
            $arrayCast[$campo] = "string";
        }

        if (isset($usaDate)) {
            $camposDataModel = app($model)->getDates();

            foreach ($camposDataModel as $key => $campo) {
                $arrayCast[$campo] = "date:Y-m-d h:i:s";
            }
        }
        
        return $arrayCast;
    }
}
