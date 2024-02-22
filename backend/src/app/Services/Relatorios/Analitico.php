<?php

namespace App\Services\Relatorios;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\VendedorCliente;
use App\Models\Tenant\Visita;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class Analitico extends BaseService
{
    private $countAgendadasErp;
    private $countAgendadasAfv;
    private $countEfetuadasErp;
    private $countEfetuadasAfv;
    private $arrayDeVisitasParaOHead;
    private $totVisitas;
    private $totAbertas;
    private $totSemVisitas;
    private $totSemVendas;
    private $totComVendas;
    private $totFinalizadas;
    private $totFinalizadoForaRaio;
    private $totFinalizadoForaRoteiro;

    public function __construct()
    {
        $this->countAgendadasErp = 0;
        $this->countAgendadasAfv = 0;
        $this->countEfetuadasErp = 0;
        $this->countEfetuadasAfv = 0;
        $this->totVisitas = 0;
        $this->totAbertas = 0;
        $this->totSemVisitas = 0;
        $this->totSemVendas = 0;
        $this->totComVendas = 0;
        $this->totFinalizadas = 0;
        $this->totFinalizadoForaRaio = 0;
        $this->totFinalizadoForaRoteiro = 0;
        $this->arrayDeVisitasParaOHead = [];
    }
    public function findAll($request)
    {

        $resultado = [];
        $resultado['head'] = [];

        $queryClientePorVendedor = VendedorCliente::select(
            "vendedor.nome as vendedor",
            "vendedor.id as vendedorId"
        )
            ->where(function ($query) use ($request) {
                if (!is_null($request->idVendedor)) $query->whereIn('vendedor.id', $request->idVendedor);
            })
            ->join('vendedor', 'vendedor_cliente.id_vendedor', 'vendedor.id')
            ->distinct('vendedor.nome')
            ->get();

        foreach ($queryClientePorVendedor as $vendedorCliente) {

            unset($vendedorCliente['totaisDetalhes']);

            $vendedorCliente['clientes'] = Cliente::select(
                "cliente.id",
                DB::raw("CONCAT(cliente.razao_social,' - ',cliente.id) AS cliente"),
                "cliente.razao_social as razaoSocial",
            )
                ->where(function ($query) use ($request) {
                    if (!is_null($request->idPedidoDispositivo)) $query->whereIn("v.id_pedido_dispositivo", $request->idPedidoDispositivo);
                    if (!is_null($request->idCliente)) $query->whereIn('cliente.id', $request->idCliente);
                })
                ->where('vendedor_cliente.id_vendedor', $vendedorCliente['vendedorId'])
                ->join('vendedor_cliente', 'cliente.id', 'vendedor_cliente.id_cliente')
                ->distinct('cliente.id')
                ->get();

            foreach ($vendedorCliente['clientes'] as $value) {
                
                unset($totVisitas);
                unset($totAbertas);
                unset($totSemVisitas);
                unset($totSemVendas);
                unset($totComVendas);
                unset($totFinalizadas);
                unset($totFinalizadoForaRaio);
                unset($totFinalizadoForaRoteiro);

                $this->countAgendadasAfv = 0;
                $this->countAgendadasErp = 0;
                $this->countEfetuadasAfv = 0;
                $this->countEfetuadasErp = 0;
                unset($arrayDeVisitas);

                $value['visitas'] = Visita::select(
                    "visita.dt_marcada as data",
                    DB::raw("CONCAT(endereco.logradouro, ' ',endereco.bairro,' ', cidade.descricao,' ',endereco.uf) as localVisita "),
                    "visita.endereco_extenso_google as localRegistrado",
                    DB::raw("DATE_FORMAT(visita.hora_inicio,'%H:%i') as inicio"),
                    DB::raw("DATE_FORMAT(visita.hora_final,'%H:%i') as fim"),
                    DB::raw("DATE_FORMAT(TIME((visita.hora_final - hora_inicio )),'%H:%i') as duracao"),
                    "visita.status",
                    "motivo.descricao as motivo",
                    "visita.observacao",
                    "visita.agendado_erp"
                )
                    ->where('visita.id_cliente', $value['id'])
                    ->where('visita.id_vendedor', $vendedorCliente['vendedorId'])
                    ->where('endereco.tit_cod', 1)
                    ->where(function ($query) use ($request) {
                        if (!is_null($request->idFilial)) $query->whereIn('filial.id', $request->idFilial);
                    })
                    ->whereBetween('visita.dt_marcada', [$request->dataInicial, $request->dataFinal])
                    ->join('vendedor', 'visita.id_vendedor', '=', 'vendedor.id')
                    ->join('cliente', 'visita.id_cliente', '=', 'cliente.id')
                    ->join('endereco', 'endereco.id_cliente', '=', 'visita.id_cliente')
                    ->join('motivo', 'visita.id_motivo', '=', 'motivo.id')
                    ->join('cidade', 'endereco.id_cidade', '=', 'cidade.id')
                    ->join('filial', 'visita.id_filial', '=', 'filial.id')
                    ->distinct('visita.id')
                    ->get();
               
                if (isset($value['visitas']) && $value['visitas'] !== []) {
                    array_push($this->arrayDeVisitasParaOHead, $arrayDeVisitas = $value['visitas']->toArray());
                }

                for ($i = 0; $i < count($arrayDeVisitas); $i++) {
                    if ($arrayDeVisitas[$i]['agendado_erp'] == 1) {
                        if ($arrayDeVisitas[$i]['status'] != 0) {
                            $this->countEfetuadasErp++;
                        }
                        $this->countAgendadasErp++;
                    } else {
                        if ($arrayDeVisitas[$i]['status'] != 0) {
                            $this->countEfetuadasAfv++;
                        }
                        $this->countAgendadasAfv++;
                    }
                }

                $value['agendadasErp'] = $this->countAgendadasErp;
                $value['agendadasAfv'] = $this->countAgendadasAfv;
                $value['efetuadasErp'] = $this->countEfetuadasErp;
                $value['efetuadasAfv'] = $this->countEfetuadasAfv;
                $value['totalAgendadas'] = $this->countAgendadasErp + $this->countAgendadasAfv;
                $value['totalEfetuadas'] = $this->countEfetuadasErp + $this->countEfetuadasAfv;

                $value['totaisDetalhes'] = $this->getDetalhes($arrayDeVisitas);

                $this->totVisitas += $value['totaisDetalhes']['totVisitas'];
                $this->totAbertas += $value['totaisDetalhes']['totAbertas'];
                $this->totSemVisitas += $value['totaisDetalhes']['totSemVisitas'];
                $this->totSemVendas += $value['totaisDetalhes']['totSemVendas'];
                $this->totComVendas += $value['totaisDetalhes']['totComVendas'];
                $this->totFinalizadas += $value['totaisDetalhes']['totFinalizadas'];
                $this->totFinalizadoForaRaio += $value['totaisDetalhes']['totFinalizadoForaRaio'];
                $this->totFinalizadoForaRoteiro += $value['totaisDetalhes']['totFinalizadoForaRoteiro'];
            }
        }

        $vendedorCliente['totaisVendedor'] = array(
            'totVisitas' => $this->totVisitas,
            'totAbertas' => $this->totAbertas,
            'totSemVistas' => $this->totSemVisitas,
            'totSemVendas' => $this->totSemVendas,
            'totComVendas' => $this->totComVendas,
            'totFinalizadas' => $this->totFinalizadas,
            'totFinalizadasForaRaio' => $this->totFinalizadoForaRaio,
            'totFinalizadosForaRoteiro' => $this->totFinalizadoForaRoteiro
        );

        $resultado['head'] = $this->getHead($this->arrayDeVisitasParaOHead);
        $resultado['clientesPorVendedor'] = $queryClientePorVendedor;

        return $resultado;
    }

    private function getDetalhes($data)
    {

        $head = array(
            'totVisitas' => 0,
            'totAbertas' => 0,
            'totSemVisitas' => 0,
            'totSemVendas' => 0,
            'totComVendas' => 0,
            'totFinalizadas' => 0,
            'totFinalizadoForaRaio' => 0,
            'totFinalizadoForaRoteiro' => 0,
        );

        foreach ($data as $key => $value) {

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
                    break;
                case 7:
                    if ($value["agendado_erp"] == 1) {
                        $head['totFinalizadoForaRaio']++;
                    } else {
                        $head['totFinalizadoForaRoteiro']++;
                        break;
                    }
            }

            $head['totVisitas']++;
        }


        return $head;
    }

    private function getHead($data)
    {
        $head = array(
            'totRegistros' => 0,
            'totAbertas' => 0,
            'totSemVisitas' => 0,
            'totSemVendas' => 0,
            'totComVendas' => 0,
            'totFinalizadas' => 0,
            'totFinalizadoForaRaio' => 0,
            'totFinalizadoForaRoteiro' => 0,
        );

        for ($i = 0; $i < count($data); $i++) {

            foreach ($data[$i] as $key => $value) {

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
                        break;
                    case 7:
                        if ($value["agendado_erp"] == 1) {
                            $head['totFinalizadoForaRaio']++;
                        } else {
                            $head['totFinalizadoForaRoteiro']++;
                            break;
                        }
                }
                $head['totRegistros']++;
            }
        }

        return $head;
    }
}
