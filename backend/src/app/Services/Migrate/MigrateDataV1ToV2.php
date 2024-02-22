<?php

namespace App\Services\Migrate;

use App\Models\Central\Empresa;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MigrateDataV1ToV2
{
    const BATCH_1000 = 1000;

    private $tenant;

    function __construct($tenant = null)
    {
        $this->tenant = $tenant;
    }

    public function migrate()
    {
        $log = [
            "sync" => [],
            "notSync" => [],
            "erros" => [],
        ];

        $this->loadOvhConnById($this->tenant);

        foreach(TENANT_TABLES as $table)
        {
            try
            {
                $data = DB::connection("empresa".$this->tenant."ovh")->table($table)->select('*')->get();
                $associativeArray = [];

                foreach ($data as $object) {
                    $associativeArray[] = get_object_vars($object);
                }
                DB::connection("empresa".$this->tenant)->statement("SET FOREIGN_KEY_CHECKS=0");
                
                $chunks = array_chunk($associativeArray, self::BATCH_1000);
                
                foreach ($chunks as $chunk) {
                    DB::connection("empresa".$this->tenant)->table($table)->insert($chunk);
                }
                DB::connection("empresa".$this->tenant)->statement("SET FOREIGN_KEY_CHECKS=1");

                $log["sync"][] = $table;
            }
            catch (\Throwable $th)
            {
                $log["notSync"][] = $table;
                $log["erros"][] = $th->getMessage() . "\n" . $th->getTraceAsString();
            }
        }

        return $log;
    }

    public function setTenant($tenant)
    {
        $this->tenant = $tenant;
    }

    private function loadOvhConnById($tenant)
    {
        $empresa = Empresa::find($tenant);
        $clone = config('database.connections.tenant');
        $ovh = config('database.connections.ovh');
        $config['host'] = $ovh['tenant']['host'];
        $clone['port'] = "{$empresa->bd_porta}";
        $clone['username'] = "{$empresa->bd_usuario}";
        $clone['password'] = "{$empresa->bd_senha}";
        $clone['database'] = "{$empresa->db_nome}";
        $clone['prefixoOnController'] = "empresa{$empresa->id}";
        Config::set("database.connections.empresa{$empresa->id}ovh", $clone);
        DB::purge("empresa{$empresa->id}ovh");
    }
}

const TENANT_TABLES = [
	"atividade",
	"aviso",
	"campanha",
	"campanha_beneficio",
	"campanha_modalidade",
	"campanha_participante",
	"campanha_requisito",
	"cidade",
	"cliente",
	"cliente_forma_pgto",
	"cliente_prazo_pgto",
	"cliente_referencia",
	"cliente_tabela_grupo",
	"cliente_tabela_preco",
	"configuracao_filial",
	"configuracao_pedidoweb",
	"contato",
	"ddd",
	"endereco",
	"estoque_cliente",
	"filial",
	"forma_pagamento",
	"forma_prazo_pgto",
	"fornecedor",
	"grupo",
	"indicador_margem",
	"integracao",
	"log",
	"meta",
	"meta_detalhe",
	"mix_produto",
	"motivo",
	"nota_fiscal",
	"nota_fiscal_item",
	"pedido",
	"pedido_item",
	"prazo_pagamento",
	"produto",
	"produto_cashback",
	"produto_descto_qtd",
	"produto_embalagem",
	"produto_estoque",
	"produto_imagem",
	"produto_ipi",
	"produto_st",
	"protabela_itens",
	"protabela_preco",
	"rastro",
	"referencia",
	"regiao",
	"rota",
	"seguro",
	"sincronizacao_loja_integrada",
	"status_cliente",
	"status_pedido",
	"status_produto",
	"subgrupo",
	"tipo_pedido",
	"titulo_financeiro",
	"uf",
	"venda_plano",
	"venda_plano_produto",
	"vendedor",
	"vendedor_cliente",
	"vendedor_prazo",
	"vendedor_produto",
	"vendedor_protabelapreco",
	"visita"
];