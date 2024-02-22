<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CabecalhoRequisicaoZip
 * 
 * @property string $token
 * @property int $fk_empresa
 * @property int|null $fk_usuario
 * @property bool|null $tipo_requisicao
 * @property int $qtd_metodo
 * @property string $metodo
 * @property string|null $recebendo_zip
 * @property string|null $metodo_em_execucao
 * @property string $caminho
 * @property Carbon $dt_inicio_envio_pacotes
 * @property Carbon|null $dt_fim_envio_pacotes
 * @property Carbon|null $dt_inicio_execucao_pacotes
 * @property Carbon|null $dt_fim_execucao_pacotes
 * @property bool $status
 * 
 * @property Empresa $empresa
 * @property Usuario|null $usuario
 * @property Collection|CorpoRequisicaoZip[] $corpo_requisicao_zips
 *
 * @package App\Models
 */
class CabecalhoRequisicaoZip extends Model
{
	protected $table = 'cabecalho_requisicao_zip';
	protected $connection = 'system';
	protected $primaryKey = 'token';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_usuario' => 'int',
		'qtd_metodo' => 'int'
	];

	protected $dates = [
		'dt_inicio_envio_pacotes',
		'dt_fim_envio_pacotes',
		'dt_inicio_execucao_pacotes',
		'dt_fim_execucao_pacotes'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'fk_empresa',
		'fk_usuario',
		'tipo_requisicao',
		'qtd_metodo',
		'metodo',
		'recebendo_zip',
		'metodo_em_execucao',
		'caminho',
		'dt_inicio_envio_pacotes',
		'dt_fim_envio_pacotes',
		'dt_inicio_execucao_pacotes',
		'dt_fim_execucao_pacotes',
		'status'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'fk_usuario');
	}

	public function corpo_requisicao_zips()
	{
		return $this->hasMany(CorpoRequisicaoZip::class, 'fk_token_cabecalho_requisicao');
	}
}
