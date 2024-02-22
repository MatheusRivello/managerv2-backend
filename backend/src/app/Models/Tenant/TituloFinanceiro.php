<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TituloFinanceiro
 * @OA\Schema 
 * Propriedades do Titulo financeiro
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_cliente", type="integer"),
 * @OA\Property(property="id_forma_pgto", type="integer"),
 * @OA\Property(property="id_vendedor", type="integer"),
 * @OA\Property(property="descricao", type="string"),
 * @OA\Property(property="id_retaguarda", type="string"),
 * @OA\Property(property="numero_doc", type="string"),
 * @OA\Property(property="tipo_titulo", type="string"),
 * @OA\Property(property="parcela", type="string"),
 * @OA\Property(property="dt_vencimento", type="string"),
 * @OA\Property(property="dt_pagamento", type="string"),
 * @OA\Property(property="dt_competencia", type="string"),
 * @OA\Property(property="dt_emissao", type="string"),
 * @OA\Property(property="valor", type="string"),
 * @OA\Property(property="multa_juros", type="string"),
 * @OA\Property(property="status", type="string"),
 * @OA\Property(property="valor_original", type="string"),
 * @OA\Property(property="linha_digitavel", type="string"),
 * @OA\Property(property="forma_pagamento", type="string"),
 * @OA\Property(property="cliente", type="string")
 * @property int $id
 * @property int $id_cliente
 * @property int|null $id_forma_pgto
 * @property int|null $id_vendedor
 * @property string $descricao
 * @property string $id_retaguarda
 * @property string $numero_doc
 * @property string $tipo_titulo
 * @property string $parcela
 * @property Carbon $dt_vencimento
 * @property Carbon|null $dt_pagamento
 * @property Carbon|null $dt_competencia
 * @property Carbon|null $dt_emissao
 * @property float $valor
 * @property float|null $multa_juros
 * @property bool $status
 * @property float|null $valor_original
 * @property string|null $linha_digitavel
 * 
 * @property FormaPagamento|null $forma_pagamento
 * @property Cliente $cliente
 *
 * @package App\Models
 */
class TituloFinanceiro extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'titulo_financeiro';
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'id_cliente' => 'string',
		'id_forma_pgto' => 'string',
		'id_vendedor' => 'string',
		'valor' => 'string',
		'multa_juros' => 'string',
		'status' => 'string',
		'valor_original' => 'string'
	];

	protected $dates = [
		'dt_vencimento',
		'dt_pagamento',
		'dt_competencia',
		'dt_emissao'
	];

	protected $fillable = [
		'id_cliente',
		'id_forma_pgto',
		'id_vendedor',
		'descricao',
		'id_retaguarda',
		'numero_doc',
		'tipo_titulo',
		'parcela',
		'dt_vencimento',
		'dt_pagamento',
		'dt_competencia',
		'dt_emissao',
		'valor',
		'multa_juros',
		'status',
		'valor_original',
		'linha_digitavel'
	];

	public function forma_pagamento()
	{
		return $this->belongsTo(FormaPagamento::class, 'id_forma_pgto');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;

		return $soma;
	}
}
