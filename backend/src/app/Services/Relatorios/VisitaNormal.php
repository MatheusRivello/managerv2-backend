<?php

namespace App\Services\Relatorios;

use App\Models\Tenant\Visita;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class VisitaNormal
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }
    public function getAll($request)
    {
        $data = $this->findAll($request);
        return [
            'head' => $this->getHead($data),
            'data' => $data
        ];
    }

    private function findAll($request)
    {
        return Visita::select(
            "visita.id",
            DB::raw("DATE_FORMAT(visita.dt_marcada, '%d/%m/%Y') as data"),
            "vendedor.nome as vendedor",
            "vendedor.id as vendedorId",
            "visita.ordem",
            "cliente.razao_social as cliente",
            DB::raw("CONCAT(endereco.logradouro, ' ',endereco.bairro, ' ',endereco.uf) as localVisita "),
            "visita.endereco_extenso_google as localRegistrado",
            DB::raw("DATE_FORMAT(visita.hora_inicio,'%H:%i') as inicio"),
            DB::raw("DATE_FORMAT(visita.hora_final,'%H:%i') as fim"),
            DB::raw("DATE_FORMAT(TIME((visita.hora_final - hora_inicio )),'%H:%i') as diferenca"),
            "visita.status",
            "motivo.descricao as motivo",
            "visita.observacao",
            "visita.agendado_erp"
        )
            ->where(function ($query) use ($request) {
                if (!is_null($request->intervaloData)) $query->whereBetween('visita.dt_marcada', $this->service->parseDate($request->intervaloData));
                if (!is_null($request->vendedorIds)) $query->whereIn('vendedor.id', $request->vendedorIds);
                if (!is_null($request->filialIds)) $query->whereIn('visita.id_filial', $request->filialIds);
                if (!is_null($request->supervisorIds)) $query->whereIn('vendedor.supervisor', $request->supervisorIds);
                if (!is_null($request->clienteIds)) $query->whereIn('visita.id_cliente', $request->clienteIds);
                if (!is_null($request->status)) $query->whereIn("visita.status", $request->status);
                if (!is_null($request->motivo)) {
                    $key = array_search("0", $request->motivo);
                    if (!is_bool($key)) $query->orWhere("visita.id_motivo", NULL);
                    $query->whereIn("visita.id_motivo", $request->motivo);
                }  
            })
            ->join('cliente', 'visita.id_cliente', '=', 'cliente.id')
            ->join('endereco', 'endereco.id_cliente', '=', 'cliente.id')
            ->join('motivo', 'visita.id_motivo', '=', 'motivo.id')
            ->join('vendedor', 'visita.id_vendedor', '=', 'vendedor.id')
            ->orderBy('visita.dt_marcada','asc')
            ->get();
    }

    private function getHead($data)
    {   
        $salesCounter = 0;
        $lastSales = [];
        $head = array(
            'totRegistros' => count($data),
            'totVendedores' => 0,
            'totAbertas' => 0,
            'totSemVisitas' => 0,
            'totSemVendas' => 0,
            'totComVendas' => 0,
            'totFinalizadas' => 0,
            'totFinalizadoForaRaio' => 0,
            'totFinalizadoForaRoteiro' => 0,
        );

        foreach ($data as $key => $value) {
            
            if(!in_array($value['vendedor'],$lastSales)){
                $lastSales[$salesCounter] = $value['vendedor'];
                $salesCounter++;
            }
            
            switch ($value["status"]) {
                case 0:
                    $head['totAbertas']++;
                    break;

                case 1:
                    $head['totSemVisitas']++;
                    break;

                case 2:
                    $head['totSemVendas']++;
                    break;

                case 3:
                    $head['totComVendas']++;
                    break;

                case 5:
                    $head['totFinalizadas']++;
                    break;
                case 6:
                    $head['totFinalizadoForaRaio']++;
                case 7:
                    if ($value["agendado_erp"] == 1) {
                        $head['totFinalizadoForaRaio']++;
                    } else {
                        $head['totFinalizadoForaRoteiro']++;
                    }
                    break;

            }
            $head['totVendedores'] = $salesCounter;
        }
        return $head;
    }
} 
