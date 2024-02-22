<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Integracao
 * 
 * @property int $id
 * @property int $id_empresa
 * @property int|null $integrador
 * @property string|null $url_base
 * @property string|null $url_loja
 * @property int|null $id_filial
 * @property int|null $id_tabela_preco
 * @property string|null $usuario
 * @property string|null $senha
 * @property string|null $campo_extra_1
 * @property string|null $campo_extra_2
 * @property string|null $campo_extra_3
 * @property string|null $campo_extra_4
 * @property string|null $campo_extra_5
 * @property Carbon|null $data_cadastro
 * @property Carbon $data_modificado
 * @property Carbon|null $data_ativacao
 * @property bool $status
 * @property Carbon|null $execucao_inicio
 * @property Carbon|null $execucao_fim
 * @property int $execucao_status
 *
 * @package App\Models
 */
class Integracao extends Model
{
	protected $table = 'integracao';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id_empresa' => 'int',
		'integrador' => 'int',
		'id_filial' => 'int',
		'id_tabela_preco' => 'int',
		'status' => 'bool',
		'execucao_status' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_modificado',
		'data_ativacao',
		'execucao_inicio',
		'execucao_fim'
	];

	protected $fillable = [
		'id_empresa',
		'integrador',
		'url_base',
		'url_loja',
		'id_filial',
		'id_tabela_preco',
		'usuario',
		'senha',
		'campo_extra_1',
		'campo_extra_2',
		'campo_extra_3',
		'campo_extra_4',
		'campo_extra_5',
		'data_cadastro',
		'data_modificado',
		'data_ativacao',
		'status',
		'execucao_inicio',
		'execucao_fim',
		'execucao_status'
	];
}
