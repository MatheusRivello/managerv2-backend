<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TotalizadorEmpresa
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property Carbon $data
 * @property int $qtd_pedido
 * @property float|null $valor_pedido
 * @property float|null $peso_bruto
 * @property float|null $peso_liquido
 * 
 * @property Empresa $empresa
 *
 * @package App\Models
 */
class TotalizadorEmpresa extends Model
{
	protected $table = 'totalizador_empresa';
	public $timestamps = false;
	public $connection = "system";

	protected $casts = [
		'fk_empresa' => 'int',
		'qtd_pedido' => 'int',
		'valor_pedido' => 'float',
		'peso_bruto' => 'float',
		'peso_liquido' => 'float'
	];

	protected $dates = [
		'data'
	];

	protected $fillable = [
		'fk_empresa',
		'data',
		'qtd_pedido',
		'valor_pedido',
		'peso_bruto',
		'peso_liquido'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}
}
