<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Meta;
use App\Models\Tenant\MetaDetalhe;

class MetaDetalheService extends IntegracaoService
{
    protected $metas;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = MetaDetalhe::class;
        $this->path = META_DETALHE_INTEG;
        $this->where = fn ($obj) => [
            'id_meta' => $this->metas[$obj->id_filial . '-' . $obj->id_meta]->id,
            'ordem' => $obj->ordem
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_meta = $this->metas[$obj->id_filial . '-' . $obj->id_meta]->id;
            $model->ordem = $obj->ordem;
            $model->descricao = $obj->descricao;
            $model->tot_cli_cadastrados = $obj->tot_cli_cadastrados;
            $model->tot_cli_atendidos = $obj->tot_cli_atendidos;
            $model->tot_qtd_ven = $obj->tot_qtd_ven;
            $model->tot_peso_ven = $obj->tot_peso_ven;
            $model->percent_tot_peso_ven = $obj->percent_tot_peso_ven;
            $model->tot_val_ven = $obj->tot_val_ven;
            $model->percent_tot_val_ven = $obj->tot_val_ven;
            $model->objetivo_vendas = $obj->objetivo_vendas;
            $model->percent_atingido = $obj->percent_atingido;
            $model->tendencia_vendas = $obj->tendencia_vendas;
            $model->percent_tendencia_ven = $obj->percent_tendencia_ven;
            $model->objetivo_clientes = $obj->objetivo_clientes;
            $model->numero_cli_falta_atender = $obj->numero_cli_falta_atender;
            $model->ped_a_faturar = $obj->ped_a_faturar;
            $model->prazo_medio = $obj->prazo_medio;
            $model->percent_desconto = $obj->percent_desconto;
            $model->tot_desconto = $obj->tot_desconto;

            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->metas = Meta::get()
        ->keyBy(function($meta) {
            return $meta->id_filial . '-' . $meta->id_retaguarda;
        });
    }
}
