<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogContato
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property int|null $id_cliente
 * @property string $nome
 * @property string $email
 * @property string|null $telefone
 * @property string $mensagem
 * @property string $ip
 * @property Carbon $dt_cadastro
 * @property Carbon|null $dt_enviado
 * @property bool $status
 *
 * @package App\Models
 */
class LogContato extends Model
{
	protected $table = 'log_contato';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'id_cliente' => 'int',
		'status' => 'bool'
	];

	protected $dates = [
		'dt_cadastro',
		'dt_enviado'
	];

	protected $fillable = [
		'fk_empresa',
		'id_cliente',
		'nome',
		'email',
		'telefone',
		'mensagem',
		'ip',
		'dt_cadastro',
		'dt_enviado',
		'status'
	];
}
