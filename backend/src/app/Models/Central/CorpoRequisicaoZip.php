<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CorpoRequisicaoZip
 * 
 * @property string $fk_token_cabecalho_requisicao
 * @property string $metodo
 * @property bool $acao
 * @property int $pacote_total
 * @property Carbon|null $dt_inicio_envio_pacote
 * @property Carbon|null $dt_fim_envio_pacote
 * @property Carbon|null $dt_inicio_execucao_pacote
 * @property Carbon|null $dt_fim_execucao_pacote
 * @property string|null $mensagem
 * @property int $status
 * 
 * @property CabecalhoRequisicaoZip $cabecalho_requisicao_zip
 * @property DetalheRequisicaoZip $detalhe_requisicao_zip
 *
 * @package App\Models
 */
class CorpoRequisicaoZip extends Model
{
	protected $table = 'corpo_requisicao_zip';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'acao' => 'bool',
		'pacote_total' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'dt_inicio_envio_pacote',
		'dt_fim_envio_pacote',
		'dt_inicio_execucao_pacote',
		'dt_fim_execucao_pacote'
	];

	protected $fillable = [
		'acao',
		'pacote_total',
		'dt_inicio_envio_pacote',
		'dt_fim_envio_pacote',
		'dt_inicio_execucao_pacote',
		'dt_fim_execucao_pacote',
		'mensagem',
		'status'
	];

	public function cabecalho_requisicao_zip()
	{
		return $this->belongsTo(CabecalhoRequisicaoZip::class, 'fk_token_cabecalho_requisicao');
	}

	public function detalhe_requisicao_zip()
	{
		return $this->hasOne(DetalheRequisicaoZip::class, 'fk_token_corpo_requisicao');
	}
}
