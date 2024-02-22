<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\StatusCliente;

class ClienteService extends IntegracaoService
{
    protected $statusCliente ;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Cliente::class;
        $this->path = CLIENTE_INTEG;
        $this->where = fn ($obj) => [
            'id_filial' => $obj->id_filial,
            'id_retaguarda' => $obj->id_retaguarda
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->id_filial = $obj->id_filial;
            $model->id_tabela_preco = $obj->id_tabela_preco;
            $model->id_prazo_pgto = $obj->id_prazo_pgto;
            $model->id_forma_pgto = $obj->id_forma_pgto;
            $model->razao_social = $obj->razao_social;
            $model->nome_fantasia = $obj->nome_fantasia;
            $model->cnpj = $obj->cnpj;
            $model->inscricao_municipal = $obj->inscricao_municipal;
            $model->inscricao_rg = $obj->inscricao_rg;
            $model->site = $obj->site;
            $model->email_nfe = $obj->email_nfe;
            $model->email = $obj->email;
            $model->id_status = $this->statusCliente[$obj->id_status]->id;
            $model->id_atividade = $obj->id_atividade;
            $model->telefone = $obj->telefone;
            $model->tipo = $obj->tipo;
            $model->tipo_contribuinte = $obj->tipo_contribuinte;
            $model->limite_credito = $obj->limite_credito;
            $model->saldo = $obj->saldo;
            $model->sinc_erp = $obj->sinc_erp;
            $model->observacao = $obj->observacao;
            $model->intervalo_visita = $obj->intervalo_visita;
            $model->dt_ultima_visita = $obj->dt_ultima_visita;
            $model->bloqueia_forma_pgto = $obj->bloqueia_forma_pgto;
            $model->bloqueia_prazo_pgto = $obj->bloqueia_prazo_pgto;
            $model->bloqueia_tabela = $obj->bloqueia_tabela;
            $model->ven_cod = $obj->ven_cod;
            $model->integra_web = $obj->integra_web;
            $model->atraso_tot = is_null($obj->atraso_tot) ? 0 : $obj->atraso_tot;
            $model->avencer = is_null($obj->avencer) ? 0 : $obj->avencer;
            $model->media_dias_atraso = $obj->media_dias_atraso;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->statusCliente = StatusCliente::get()->keyBy('id_retaguarda');
    }
}
