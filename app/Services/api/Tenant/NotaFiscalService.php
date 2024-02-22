<?php

namespace App\Services\api\Tenant;

use App\Models\Tenant\NotaFiscal;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class NotaFiscalService extends BaseService
{
    public function getMargemMarkup($request)
    {
        switch ($request->tipo) {
            case VENDEDOR_TIPO_RETORNO:
                $resultado = $this->getVendedorMargemMarkup();
                break;

            case CLIENTE_TIPO_RETORNO:
                $resultado = $this->getClienteMargemMarkup();
                break;

            case ATIVIDADE_TIPO_RETORNO:
                $resultado = $this->getAtividadeMargemMarkup();
                break;

            case PRODUTO_TIPO_RETORNO:
                $resultado = $this->getProdutoMargemMarkup();
                break;

            case GRUPO_TIPO_RETORNO:
                $resultado = $this->getGrupoMargemMarkup();
                break;

            case SUBGRUPO_TIPO_RETORNO:
                $resultado = $this->getSubGrupoMargemMarkup();
                break;

            case MODO_DE_COBRANCA_TIPO_RETORNO:
                $resultado = $this->getModoCobrancaMargemMarkup();
                break;

            case PRAZO_DE_COBRANCA_TIPO_RETORNO:
                $resultado = $this->getPrazoPagamentoMargemMarkup();
                break;

            default:
                $resultado = $this->getVendedorMargemMarkup();
                break;
        }

        return $resultado;
    }


    protected function getVendedorMargemMarkup()
    {
        $resultado = NotaFiscal::select(
            "vendedor.nome AS descricao",
            "nota_fiscal.nfs_custo",
            DB::raw("(nota_fiscal.nfs_valbrut - nota_fiscal.nfs_custo) AS nfs_rentabilidade"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.nfs_valbrut) *100)), 0) AS nfs_margem"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.nfs_valbrut / nota_fiscal.nfs_custo) * 100) -100, 0) AS nfs_markup")
        )
            ->join("vendedor", "nota_fiscal.id_vendedor", "=", "vendedor.id");


        $resultado->groupBy("vendedor.nome", "nota_fiscal.nfs_custo", "nota_fiscal.nfs_valbrut");

        return $resultado->distinct()->paginate(20);
    }

    protected function getClienteMargemMarkup()
    {
        $resultado = NotaFiscal::select(
            "nota_fiscal.nfs_custo",
            DB::raw("IF(cliente.razao_social = '' OR cliente.razao_social IS NULL,cliente.nome_fantasia,cliente.razao_social) AS descricao"),
            DB::raw("(nota_fiscal.nfs_valbrut - nota_fiscal.nfs_custo) AS nfs_rentabilidade"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.nfs_valbrut) *100)), 0) AS nfs_margem"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.nfs_valbrut / nota_fiscal.nfs_custo) * 100) -100, 0) AS nfs_markup")
        )
            ->join("cliente", "nota_fiscal.id_cliente", "=", "cliente.id");


        $resultado->groupBy("descricao", "nota_fiscal.nfs_custo", "nota_fiscal.nfs_valbrut");

        return $resultado->distinct()->paginate(20);
    }

    protected function getAtividadeMargemMarkup()
    {
        $resultado = NotaFiscal::select(
            "atividade.descricao",
            "nota_fiscal.nfs_custo",
            DB::raw("(nota_fiscal.nfs_valbrut - nota_fiscal.nfs_custo) AS nfs_rentabilidade"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.nfs_valbrut) *100)), 0) AS nfs_margem"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.nfs_valbrut / nota_fiscal.nfs_custo) * 100) -100, 0) AS nfs_markup")
        )
            ->join("cliente", "nota_fiscal.id_cliente", "=", "cliente.id")
            ->join("atividade", "cliente.id_atividade", "=", "atividade.id");

        $resultado->groupBy("descricao", "nota_fiscal.nfs_custo", "nota_fiscal.nfs_valbrut");

        return $resultado->distinct()->paginate(20);
    }

    protected function getProdutoMargemMarkup()
    {
        $resultado = NotaFiscal::select(
            "produto.descricao",
            "nota_fiscal_item.nfs_custo",
            DB::raw("(nota_fiscal_item.nfs_total - nota_fiscal_item.nfs_custo) AS nfs_rentabilidade"),
            DB::raw("IF (nota_fiscal_item.nfs_custo > 0, (100 - ((nota_fiscal_item.nfs_custo / nota_fiscal_item.nfs_total) *100)), 0) AS nfs_margem"),
            DB::raw("IF (nota_fiscal_item.nfs_custo > 0, ((nota_fiscal_item.nfs_total / nota_fiscal_item.nfs_custo) * 100) -100, 0) AS nfs_markup")
        )
            ->join("nota_fiscal_item", "nota_fiscal.ped_num", "=", "nota_fiscal_item.ped_num")
            ->join("produto", "nota_fiscal_item.id_produto", "=", "produto.id");

        $resultado->groupBy("produto.descricao", "produto.id", "nota_fiscal_item.nfs_custo", "nota_fiscal_item.nfs_total");

        return $resultado->distinct()->paginate(20);
    }

    protected function getGrupoMargemMarkup()
    {
        $resultado = NotaFiscal::select(
            "grupo.grupo_desc AS descricao",
            "nota_fiscal_item.nfs_custo",
            DB::raw("(nota_fiscal_item.nfs_total - nota_fiscal_item.nfs_custo) AS nfs_rentabilidade"),
            DB::raw("IF (nota_fiscal_item.nfs_custo > 0, (100 - ((nota_fiscal_item.nfs_custo / nota_fiscal_item.nfs_total) *100)), 0) AS nfs_margem"),
            DB::raw("IF (nota_fiscal_item.nfs_custo > 0, ((nota_fiscal_item.nfs_total / nota_fiscal_item.nfs_custo) * 100) -100, 0) AS nfs_markup")
        )
            ->join("nota_fiscal_item", "nota_fiscal.ped_num", "=", "nota_fiscal_item.ped_num")
            ->join("produto", "nota_fiscal_item.id_produto", "=", "produto.id")
            ->join("grupo", "produto.id_grupo_new", "=", "grupo.id");

        $resultado->groupBy("grupo.grupo_desc", "grupo.id", "nota_fiscal_item.nfs_custo", "nota_fiscal_item.nfs_total");

        return $resultado->distinct()->paginate(20);
    }

    protected function getSubGrupoMargemMarkup()
    {
        $resultado = NotaFiscal::select(
            "subgrupo.subgrupo_desc AS descricao",
            "nota_fiscal_item.nfs_custo",
            DB::raw("(nota_fiscal_item.nfs_total - nota_fiscal_item.nfs_custo) AS nfs_rentabilidade"),
            DB::raw("IF (nota_fiscal_item.nfs_custo > 0, (100 - ((nota_fiscal_item.nfs_custo / nota_fiscal_item.nfs_total) *100)), 0) AS nfs_margem"),
            DB::raw("IF (nota_fiscal_item.nfs_custo > 0, ((nota_fiscal_item.nfs_total / nota_fiscal_item.nfs_custo) * 100) -100, 0) AS nfs_markup")
        )
            ->join("nota_fiscal_item", "nota_fiscal.ped_num", "=", "nota_fiscal_item.ped_num")
            ->join("produto", "nota_fiscal_item.id_produto", "=", "produto.id")
            ->join("subgrupo", "produto.id_subgrupo_new", "=", "subgrupo.id");

        $resultado->groupBy("subgrupo.subgrupo_desc", "subgrupo.id", "nota_fiscal_item.nfs_custo", "nota_fiscal_item.nfs_total");

        return $resultado->distinct()->paginate(20);
    }

    protected function getModoCobrancaMargemMarkup()
    {
        $resultado = NotaFiscal::select(
            "forma_pagamento.descricao",
            "nota_fiscal.nfs_custo",
            DB::raw("(nota_fiscal.nfs_valbrut - nota_fiscal.nfs_custo) AS nfs_rentabilidade"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.nfs_valbrut) *100)), 0) AS nfs_margem"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.nfs_valbrut / nota_fiscal.nfs_custo) * 100) -100, 0) AS nfs_markup")
        )
            ->join("forma_pagamento", "nota_fiscal.forma_pag", "=", "forma_pagamento.id_retaguarda");

        $resultado->groupBy("nota_fiscal.forma_pag", "forma_pagamento.descricao", "nota_fiscal.nfs_valbrut", "nota_fiscal.nfs_custo");

        return $resultado->distinct()->paginate(20);
    }

    protected function getPrazoPagamentoMargemMarkup()
    {
        $resultado = NotaFiscal::select(
            "prazo_pagamento.descricao",
            "nota_fiscal.nfs_custo",
            DB::raw("(nota_fiscal.nfs_valbrut - nota_fiscal.nfs_custo) AS nfs_rentabilidade"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.nfs_valbrut) *100)), 0) AS nfs_margem"),
            DB::raw("IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.nfs_valbrut / nota_fiscal.nfs_custo) * 100) -100, 0) AS nfs_markup")
        )
            ->join("prazo_pagamento", "nota_fiscal.prazo_pag", "=", "prazo_pagamento.id_retaguarda");

        $resultado->groupBy("prazo_pagamento.descricao","nota_fiscal.prazo_pag", "prazo_pagamento.descricao", "nota_fiscal.nfs_valbrut", "nota_fiscal.nfs_custo");

        return $resultado->distinct()->paginate(20);
    }
}
