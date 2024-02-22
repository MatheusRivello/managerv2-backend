<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Cliente;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ClienteExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Cliente::class;
        $this->filters = ['id', 'id_filial', 'id_retaguarda', 'id_tabela_preco', 'id_prazo_pgto', 'id_forma_pgto', 'id_status', 'razao_social', 'nome_fantasia', 'cnpj', 'sinc_erp'];
        $this->tabela='cliente';
        $this->modelComBarra='\Cliente';
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CLIENTE_EXTERNA;
        $this->firstOrNew = ['id', 'id_filial', 'id_retaguarda', 'id_tabela_preco', 'id_prazo_pgto', 'id_forma_pgto', 'id_status'];
        $this->fields = [
            'id_filial',
            'id_retaguarda',
            'id_tabela_preco',
            'id_prazo_pgto',
            'id_forma_pgto',
            'id_status',
            'razao_social',
            'nome_fantasia',
            'cnpj',
            'senha',
            'email',
            'codigo_tempo',
            'codigo_senha',
            'id_atividade',
            'telefone',
            'tipo',
            'tipo_contribuinte',
            'site',
            'email_nfe',
            'limite_credito',
            'saldo',
            'saldo_credor',
            'sinc_erp',
            'observacao',
            'intervalo_visita',
            'dt_ultima_visita',
            'dt_cadastro',
            'dt_modificado',
            'bloqueia_forma_pgto',
            'bloqueia_prazo_pgto',
            'bloqueia_tabela',
            'id_mobile',
            'inscricao_municipal',
            'inscricao_rg',
            'ven_cod',
            'integra_web',
            'atraso_tot',
            'avencer',
            'media_dias_atraso',
            'dt_ultima_compra'
        ];
    }

    public function storeCliente(Request $request)
    {
        $where = ['id_filial'=>$request->idFilial, 'id_retaguarda'=>$request->idRetaguarda, 'id_tabela_preco'=>$request->idTabelaPreco, 'id_prazo_pgto'=>$request->idPrazoPgto, 'id_forma_pgto'=>$request->idFormaPgto, 'id_status'=>$request->idStatus];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }
}
