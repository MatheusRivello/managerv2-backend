<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProdutoImagem
 * 
 * @property int $id
 * @property int $id_produto
 * @property string|null $caminho
 * @property bool|null $padrao
 * @property int|null $sequencia
 * @property string|null $url
 * @property Carbon $dt_atualizacao
 * 
 * @property Produto $produto
 *
 * @package App\Models
 */
class ProdutoImagem extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'produto_imagem';
	public $timestamps = false;

	protected $casts = [
		'id_produto' => 'int',
		'sequencia' => 'int'
	];

	protected $dates = [
		'dt_atualizacao'
	];

	protected $fillable = [
		'id_produto',
		'caminho',
		'padrao',
		'sequencia',
		'url',
		'dt_atualizacao'
	];

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}
}
