<?php

namespace App\Services\Integracao\Traits;

trait Database
{
    private function updateOrCreate() {
        $data = $this->data;
        $updateFields = $this->updateFields;
        $where = $this->where;
        $saved = 0;
        $notSalved = 0;
        foreach ($data as $key => $obj) {
            try
            {
                $clazz = new \stdClass();

                $model = $this->ModelClass::updateOrCreate(
                    $where($obj),
                    (array) $updateFields($clazz, $obj)
                );

                if (isset($model)) $saved++;

                $this->counter++;
            }
            catch (\Throwable $th)
            {
                if ($this->isUndefinedIndexOrOffset($th)) {
                    $this->addLog("Tabela referenciada por " . $this->modelName . " não possui o registro com este id_filial-id_retaguarda. " . $th->getMessage(), $th);
                }
                else {
                    $this->addLog("Erro em " . $this->modelName . " com status " . $th->getCode() . ". " . $th->getMessage(), $th);
                }
                $notSalved++;
            }
        }
        $this->saveLog("erros-detalhados/" . date("H\h"));
        
        return [
            'saved' => $saved,
            'notSalved' => $notSalved
        ];
    }

    public function getTablesThatDataCanBeExcluded() {
        return [
            [
                'Aviso' => 'aviso',
                'Campanha' => 'campanha',
                'CampanhaBeneficio' => 'campanha_beneficio',
                'CampanhaModalidade' => 'campanha_modalidade',
                'CampanhaParticipante' => 'campanha_participante',
                'CampanhaRequisito' => 'campanha_requisito',
                'ClienteFormaPgto' => 'cliente_forma_pgto',
                'ClientePrazoPgto' => 'cliente_prazo_pgto',
                'ClienteTabelaGrupo' => 'cliente_tabela_grupo',
                'ClienteTabelaPreco' => 'cliente_tabela_preco',
                'ConfigFilial' => 'configuracao_filial',
                'FormaPrazoPagamento' => 'forma_prazo_pgto',
                'IndicadorMargem' => 'indicador_margem',
                'MetaDetalhe' => 'meta_detalhe',
                'MixProduto' => 'mix_produto',
                'PrazoPagamento' => 'prazo_pagamento',
                'ProdutoDescontoQuantidade' => 'produto_descto_qtd',
                'ProdutoEmbalagem' => 'produto_imagem',
                'ProdutoEstoque' => 'produto_estoque',
                'ProdutoIPI' => 'produto_ipi',
                'ProdutoST' => 'produto_st',
                'ProdutoTabelaItens' => 'protabela_itens',
                'Contato' => 'contato',
                'Endereco' => 'endereco',
                'VendaPlano' => 'venda_plano',
                'VendedorCliente' => 'vendedor_cliente',
                'VendedorProduto' => 'vendedor_produto',
                'VendedorProtabelaPreco' => 'vendedor_protabelapreco',
            ]
        ];
    }

    public function getTablesThatDataCanBeInactivated() {
        return [
            [
                'Atividade' => 'atividade',
                'Motivo' => 'motivo',
                'FormaPagamento' => 'forma_pagamento',
                'StatusCliente' => 'status_cliente',
                'StatusProduto' => 'status_produto',
                'TipoPedido' => 'tipo_pedido',
            ]
        ];
    }
}