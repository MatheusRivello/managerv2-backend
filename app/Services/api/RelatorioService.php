<?php

namespace App\Services\api;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class RelatorioService extends BaseService
{
    public function getArrayData($request)
    {
        $datakeys = collect(explode(',', $request->datakey))->map(function ($datakey) {
            $campos = explode(":", $datakey);

            return array("datakey" => trim($campos[0]), "name" => trim($campos[1]));
        });

        $data = DB::select($request->query);

        $array = array(
            "id" => $request->id,
            "name" => $request->titulo,
            "properties" => json_decode($datakeys),
            "data" =>  $data
        );

        return $array;
    }

    public function getArrayRelatorios($request)
    {

        $reports = collect($request)->map(function ($head) {

            $arrayReport = [];

            foreach ($head->relatorios as $key) {
                $report = array(
                    "id" => $key->id,
                    "reportName" => $key->titulo,
                    "relativePath" => $key->slug,
                    "imageUrl" => url($key->image)
                );

                array_push($arrayReport, $report);
            }

            return array(
                "id" => $head->id,
                "groupName" => $head->descricao,
                "reports" => $arrayReport
            );;
        });

        return $reports;
    }
}
