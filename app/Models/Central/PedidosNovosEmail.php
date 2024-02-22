<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PedidosNovosEmail
 * 
 * @property int $id
 * @property int $id_pedido
 * @property int $fk_empresa
 * 
 * @property Empresa $empresa
 *
 * @package App\Models
 */
class PedidosNovosEmail extends Model
{
	protected $table = 'pedidos_novos_email';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id_pedido' => 'int',
		'fk_empresa' => 'int'
	];

	protected $fillable = [
		'id_pedido',
		'fk_empresa'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}
}
