<?php

namespace App\Services\Relatorios;

use App\Models\Central\PeriodoSincronizacao;
use App\Models\Tenant\Cliente;
use App\Models\Tenant\Vendedor;
use App\Models\Tenant\Visita;
use App\Models\Tenant\VendedorCliente;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class VisitaEfetuadas extends BaseService
{

    public function findAll($request)
    {
        
        $servico = new BaseService;
        $configEmpresa = PeriodoSincronizacao::select('restricao_vendedor_cliente')
            ->where('fk_empresa', $servico->usuarioLogado()->fk_empresa)
            ->get();

        $configEmpresa = ($configEmpresa->count() > 0) ? $configEmpresa->toArray()[0]["restricao_vendedor_cliente"] : FALSE;

        $registros = Visita::select(
            "cliente.id as id",
            "cliente.id_retaguarda as cod",
            "cliente.razao_social as razaoSocial",
            DB::raw("count(visita.id_cliente) as agendadas")
        )
            ->where(function ($query) use ($request){
                if (!is_null($request->idFilial)) $query->whereIn("visita.id_filial", $request->idFilial);
                if (!is_null($request->idVendedor)) $query->whereIn("vendedor.id", $request->idVendedor);
                if (!is_null($request->idCliente)) $query->whereIn("cliente.id_retaguarda", $request->idCliente);
                if (!is_null($request->idPedidoDispositivo)) $query->whereIn("visita.id_pedido_dispositivo", $request->idPedidoDispositivo);
            })
            ->join('cliente', 'visita.id_cliente', '=', 'cliente.id')
            ->join('vendedor', 'vendedor.id', '=', 'visita.id_vendedor')
            ->groupBy('cliente.id')
            ->groupBy('cliente.id_retaguarda')
            ->groupBy('cliente.razao_social')
            ->get();

        $resultados = [];
        $resultados['head'] = [];
        $resultados['clientesComVisitas'] = [];
        $resultados['clientesSemVisitas'] = [];

        $idNotInCliente = [];
        $registroVendedores = Vendedor::select()->get();
        $vendedores = $registroVendedores->count() > 0 ? $registroVendedores->toArray() : null;

        if (isset($vendedores)) {
            if ($configEmpresa == TRUE) {
                $id_vendedor = "id_vendedor";
                $tabela = "vendedor_cliente";
            } else {
                $id_vendedor = "ven_cod";
                $tabela = "cliente";
            }
        }
        Cliente::select('"cliente.id_retaguarda",
        cliente.razao_social"')
            ->where("vc.$id_vendedor")
            ->where_not_in("cliente.id_retaguarda", $idNotInCliente)
            ->orderBy("c.razao_social")
            ->join($tabela, 'cliente.id', "$tabela.id_cliente");

        $queryCarteira = VendedorCliente::select(
            "vendedor_cliente.id_cliente as id",
            "cliente.id_retaguarda as cod",
            "cliente.razao_social as razaoSocial "
        )
            ->join('cliente', "vendedor_cliente.id_cliente", "=", "cliente.id")
            ->get();

        $carteiraClientes = ($queryCarteira->count() > 0) ? $queryCarteira->toArray() : NULL;
        $qtdSemVisitas = $queryCarteira->count();

        $resultados['head'] = self::montarHead($registros, $qtdSemVisitas);

        $resultados['clientesComVisitas'] = self::getClientesComVisitas($registros, $request->intervaloData);
        $resultados['clientesSemVisitas'] = $carteiraClientes;

        return $resultados;
    }

    public function getClientesComVisitas($clientes, array $filtroData)
    {
        $clientesComVisita = [];
        

        foreach ($clientes as $key => $cliente) {

            $registro = Visita::select(
                DB::raw("COUNT(visita.id_cliente) as concluidas"),
                DB::raw("CONCAT(FORMAT((COUNT(visita.id_cliente)/{$cliente->agendadas}*100),0), '%') as porcentagem ")
            )
                ->whereBetween('visita.dt_marcada', [$filtroData['0'], $filtroData['1']])
                ->join('cliente', 'visita.id_cliente', '=', 'cliente.id')
                ->groupBy('visita.id_cliente')
                ->orderBy('visita.id_cliente')
                ->get();

            $carteiraClientes = $registro->count() > 0 ? $registro->toArray() : null;
            $cliente['efetuadas'] = isset($carteiraClientes[0]['concluidas']) ? $carteiraClientes[0] : 0;

            unset($carteiraClientes);

            array_push($clientesComVisita, $cliente);
        }
        return $clientesComVisita;
    }

    public function montarHead($registros, $semVisitas)
    {
        $head = [];
        $qtdRegistroComVisitas = 0;
        $qtdAgendadas = 0;
        $qtdEfetuadas = 0;
        $qtdCliEfetuadas = 0;

        foreach ($registros as $key => $value) {

            $qtdRegistroComVisitas++;
            $qtdAgendadas += $value["visitasAgendadas"];
            $qtdEfetuadas += $value["visitasEfetuadas"];
            $qtdCliEfetuadas += intval($value["visitasEfetuadas"]);
        }
        $head = [
            'totComVisita' => $qtdRegistroComVisitas,
            'totSemVisitas' => $semVisitas,
            'totAgendadasConsolidados' => $qtdRegistroComVisitas,
            'totAgendadasDetalhados' => $qtdAgendadas,
            'totEfetuadasDetalhados' =>  $qtdEfetuadas,
            'totEfetuadasConsolidados' => $qtdCliEfetuadas
        ];

        return $head;
    }
}
