<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetalheRequisicaoZip
 * 
 * @property string $fk_token_corpo_requisicao
 * @property int $pacote_atual
 * @property Carbon $dt_inicio
 * @property Carbon|null $dt_fim
 * @property Carbon|null $dt_inicio_execucao
 * @property Carbon|null $dt_fim_execucao
 * @property string|null $mensagem
 * @property bool $status
 * 
 * @property CorpoRequisicaoZip $corpo_requisicao_zip
 *
 * @package App\Models
 */
class DetalheRequisicaoZip extends Model
{
	protected $table = 'detalhe_requisicao_zip';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'pacote_atual' => 'int',
		'status' => 'bool'
	];

	protected $dates = [
		'dt_inicio',
		'dt_fim',
		'dt_inicio_execucao',
		'dt_fim_execucao'
	];

	protected $fillable = [
		'fk_token_corpo_requisicao',
		'pacote_atual',
		'dt_inicio',
		'dt_fim',
		'dt_inicio_execucao',
		'dt_fim_execucao',
		'mensagem',
		'status'
	];

	public function corpo_requisicao_zip()
	{
		return $this->belongsTo(CorpoRequisicaoZip::class, 'fk_token_corpo_requisicao');
	}
}
