<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConfiguracaoPedidoweb
 * 
 * @property int $id
 * @property string|null $descricao
 * @property string|null $valor
 * @property int|null $tipo
 * @property string|null $label
 * @property string|null $valor_padrao
 * @property string|null $campo
 * @property string|null $tamanho_maximo
 * @property string|null $tabela_bd
 * @property string|null $info_tabela
 *
 * @package App\Models
 */
class ConfiguracaoPedidoweb extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'configuracao_pedidoweb';
	public $timestamps = false;

	protected $casts = [
		'tipo' => 'int'
	];

	protected $fillable = [
		'descricao',
		'valor',
		'tipo',
		'label',
		'valor_padrao',
		'campo',
		'tamanho_maximo',
		'tabela_bd',
		'info_tabela'
	];
}
