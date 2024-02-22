<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * 
 * @property int $id
 * @property int|null $tipo
 * @property string|null $tipo_acesso
 * @property int|null $fk_empresa
 * @property int|null $fk_usuario
 * @property int|null $id_cliente
 * @property string|null $ip
 * @property Carbon $dt_cadastro
 * @property string|null $tabela
 * @property string|null $mensagem
 * @property string|null $conteudo
 *
 * @package App\Models
 */
class Log extends Model
{
	protected $table = 'log';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'tipo' => 'int',
		'fk_empresa' => 'int',
		'fk_usuario' => 'int',
		'id_cliente' => 'int'
	];

	protected $dates = [
		'dt_cadastro'
	];

	protected $fillable = [
		'tipo',
		'tipo_acesso',
		'fk_empresa',
		'fk_usuario',
		'id_cliente',
		'ip',
		'dt_cadastro',
		'tabela',
		'mensagem',
		'conteudo'
	];
}
