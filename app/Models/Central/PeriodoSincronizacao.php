<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PeriodoSincronizacao
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property int|null $fk_usuario
 * @property string $dia
 * @property string|null $hora
 * @property int|null $periodo
 * @property int|null $registro_lote
 * @property int|null $qtd_dias_nota_fiscal
 * @property int|null $qtd_dias_nota_fiscal_app
 * @property bool|null $restricao_produto
 * @property bool|null $restricao_protabela_preco
 * @property bool|null $restricao_vendedor_cliente
 * @property bool|null $restricao_supervisor_cliente
 * @property Carbon|null $dt_cadastro_online
 * @property Carbon|null $dt_execucao_online
 * @property Carbon|null $dt_execucao_online_fim
 * @property bool|null $baixar_online
 * @property string|null $token_online_processando
 * 
 * @property Empresa $empresa
 *
 * @package App\Models
 */
class PeriodoSincronizacao extends Model
{
	protected $table = 'periodo_sincronizacao';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_usuario' => 'int',
		'periodo' => 'int',
		'registro_lote' => 'int',
		'qtd_dias_nota_fiscal' => 'int',
		'qtd_dias_nota_fiscal_app' => 'int',
		'restricao_produto' => 'int',
		'restricao_protabela_preco' => 'int',
		'restricao_vendedor_cliente' => 'int',
		'restricao_supervisor_cliente' => 'int',
		'baixar_online' => 'int'
	];

	protected $dates = [
		'dt_cadastro_online',
		'dt_execucao_online',
		'dt_execucao_online_fim'
	];

	protected $hidden = [
		"token_online_processando"
	];

	protected $fillable = [
		'fk_empresa',
		'fk_usuario',
		'dia',
		'hora',
		'periodo',
		'registro_lote',
		'qtd_dias_nota_fiscal',
		'qtd_dias_nota_fiscal_app',
		'restricao_produto',
		'restricao_protabela_preco',
		'restricao_vendedor_cliente',
		'restricao_supervisor_cliente',
		'dt_cadastro_online',
		'dt_execucao_online',
		'dt_execucao_online_fim',
		'baixar_online',
		'job_id',
		'job_processando'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}
}
