<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PedidoAutorizacao
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property int $fk_usuario
 * @property int $id_pedido
 * @property bool $liberado
 * @property string|null $observacao
 * @property string $ip
 * @property Carbon $dt_cadastro
 * 
 * @property Empresa $empresa
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class PedidoAutorizacao extends Model
{
	protected $table = 'pedido_autorizacao';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_usuario' => 'int',
		'id_pedido' => 'int',
		'liberado' => 'bool'
	];

	protected $dates = [
		'dt_cadastro'
	];

	protected $fillable = [
		'fk_empresa',
		'fk_usuario',
		'id_pedido',
		'liberado',
		'observacao',
		'ip',
		'dt_cadastro'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'fk_usuario');
	}
}
