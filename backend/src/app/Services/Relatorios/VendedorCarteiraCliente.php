<?php

namespace App\Services\Relatorios;

use App\Models\Central\PeriodoSincronizacao;

use App\Models\Tenant\Vendedor;
use App\Models\Tenant\Visita;
use App\Models\Tenant\VendedorCliente;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;


class VendedorCarteiraCliente extends BaseService
{
    private $servico;

    public function __construct()
    {
        $this->servico = new BaseService;
    }
    public function findAll($request)
    {
        $configEmpresa = PeriodoSincronizacao::select('restricao_vendedor_cliente')
            ->where('fk_empresa', $this->servico->usuarioLogado()->fk_empresa)
            ->get();

        $configEmpresa = ($configEmpresa->count() > 0) ? $configEmpresa->toArray()[0]["restricao_vendedor_cliente"] : FALSE;

        $registros = Vendedor::select(
            "visita.id",
            "vendedor.id as idVendedor",
            "vendedor.nome as vendedor",
            "filial.emp_fan as filial",
            DB::raw("IF(visita.dt_marcada is null,visita.dt_cadastro,visita.dt_marcada) as data"),
            DB::raw("DATE_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(visita.hora_final, visita.hora_inicio)))), '%H:%i:%s') as tempoTotal"),
            DB::raw(
                "DATE_FORMAT(
            SEC_TO_TIME(
            SUM(
                TIME_TO_SEC(
                    TIMEDIFF(visita.hora_final, visita.hora_inicio)
                    )
                ) / count(visita.id_cliente) 
            ), '%H:%i:%s') as tempoMedio"

            )
        )
            ->where(function ($query) use ($request) {
                if (!is_null($request->idFilial)) $query->where("visita.id_filial", $request->idFilial);
                if (!is_null($request->idVendedor)) $query->where("vendedor.id", $request->idVendedor);
                if (!is_null($request->idCliente)) $query->where("cliente.id", $request->idCliente);
                if (!is_null($request->idPedidoDispositivo)) $query->where("visita.id_pedido_dispositivo", $request->idPedidoDispositivo);
                if (!is_null($request->dataInicial) && !is_null($request->dataFinal)) $query->whereBetween('visita.dt_marcada', [$request->dataInicial, $request->dataFinal]);
                if (!is_null($request->status)) $query->where('visita.status',$request->status);
                if (!is_null($request->idMotivo)) $query->where('visita.id_motivo',$request->idMotivo);
            })

            ->join('visita', 'vendedor.id', '=', 'visita.id_vendedor')
            ->join('filial', 'visita.id_filial', '=', 'filial.id')
            ->join('cliente', 'visita.id_cliente', '=', 'cliente.id')
            ->join('motivo', 'visita.id_motivo', '=', 'motivo.id')
            ->groupBy('visita.id')
            ->groupBy('vendedor.id')
            ->groupBy('vendedor.nome')
            ->groupBy('filial.emp_fan')
            ->groupBy('visita.dt_marcada')
            ->groupBy('visita.dt_cadastro')
            ->get();

        $vendedores = ($registros->count() > 0) ? $registros->toArray() : 0;

        $cont = 0;
        $resultados = [];
        $resultados['head'] = [];
        $resultados['data'] = [];
        $queryCarteira = null;
        $queryVisitas = null;
        $totAgendadas = 0;

        if ($vendedores != null) {
            foreach ($vendedores as $key => $vendedor) {

                if ($configEmpresa == TRUE) {

                    $id_cliente = "id_cliente";
                    $id_vendedor = "id_vendedor";

                    $queryCarteira = VendedorCliente::select(DB::raw("COUNT(vendedor_cliente.$id_cliente) as carteiraClientes"))
                        ->where("vendedor_cliente.id_vendedor", $vendedor["idVendedor"])
                        ->get();
                } else {

                    $id_cliente = "id";
                    $id_vendedor = "ven_cod";

                    $queryCarteira = DB::table('cliente as cli')
                        ->select(DB::raw("COUNT(cli.$id_cliente) as carteiraClientes"))
                        ->where("cli.id_vendedor", $vendedor["idVendedor"])
                        ->get();
                }

                $carteiraClientes = ($queryCarteira->count() > 0) ? $queryCarteira->toArray() : 0;
                $vendedor['carteira'] = $carteiraClientes == 0 ? 0 : $carteiraClientes['0'];

                unset($carteiraClientes);

                $queryCarteira = Visita::select('visita.id_cliente as visitasAgendadas')
                    ->where("visita.id_filial", $request->idFilial)
                    ->where("visita.id_vendedor", $vendedor['idVendedor'])
                    ->where(function ($query) use ($request) {
                        if (!is_null($request->dataInicial && $request->dataFinal)) $query->whereBetween('visita.dt_marcada', [$request->dataInicial, $request->dataFinal]);
                    })
                    ->distinct()
                    ->get();

                $vendedor['agendadas'] = $queryCarteira->count() > 0 ? $queryCarteira->count() : 0;

                $queryVisitas = Visita::select("visita.id_cliente as agendadas")
                    ->where('visita.id_vendedor', $vendedor['idVendedor'])
                    ->where(function ($query) use ($request) {
                        if (!is_null($request->dataInicial) && !is_null($request->dataFinal)) $query->whereBetween('visita.dt_marcada', [$request->dataInicial, $request->dataFinal]);
                    })
                    ->distinct()
                    ->get();

                $vendedor['efetuadas'] = Visita::select("visita.id_cliente as agendadas")
                    ->where('visita.id_vendedor', $vendedor['idVendedor'])
                    ->where(function ($query) use ($request) {
                        if (!is_null($request->dataInicial) && !is_null($request->dataFinal)) $query->whereBetween('visita.dt_marcada', [$request->dataInicial, $request->dataFinal]);
                    })
                    ->where('visita.status', [STATUS_VISITA_SEM_VENDA, STATUS_VISITA_COM_VENDA, STATUS_VISITA_COM_VENDA, STATUS_VISITA_FINALIZADA_AFV])
                    ->distinct()
                    ->get();

                if ($vendedor['efetuadas']->count() > 0) {
                    $vendedor['efetuadas'] = $queryVisitas->count();
                    $cont++;
                } else {
                    $vendedor['efetuadas'] = 0;
                }
                $totAgendadas += $vendedor['agendadas'];
                array_push($resultados['data'], $vendedor);
            }
        }
        $countVendedores = $vendedores > 0 && count($vendedores) > 0 ? count($vendedores) : 0;

        $resultados['head'] = [
            'totRegistros' => $countVendedores,
            'totEfetuadas' => $cont,
            'totAgendadas' => $totAgendadas,
            'totVendedores' => $countVendedores
        ];


        return  $resultados;
    }
}
