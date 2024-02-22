<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CorpoDdl
 * 
 * @property int $id
 * @property int $id_empresa
 * @property int $id_cabecalho_ddl
 * @property string|null $codigo
 * @property string|null $codigo_sem_tags
 * @property bool|null $status
 * @property Carbon|null $dt_cadastro
 * @property Carbon|null $dt_modificado
 * @property Carbon|null $dt_injecao_ddl
 * @property Carbon|null $dt_status_modificado
 * @property bool|null $status_modificado_por_ultimo
 * 
 * @property CabecalhoDdl $cabecalho_ddl
 * @property Empresa $empresa
 * @property Collection|HistoricoDdl[] $historico_ddls
 *
 * @package App\Models
 */
class CorpoDdl extends Model
{
	protected $table = 'corpo_ddl';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id_empresa' => 'int',
		'id_cabecalho_ddl' => 'int',
		'status' => 'bool',
		'status_modificado_por_ultimo' => 'bool'
	];

	protected $dates = [
		'dt_cadastro',
		'dt_modificado',
		'dt_injecao_ddl',
		'dt_status_modificado'
	];

	protected $fillable = [
		'id_empresa',
		'id_cabecalho_ddl',
		'codigo',
		'codigo_sem_tags',
		'status',
		'dt_cadastro',
		'dt_modificado',
		'dt_injecao_ddl',
		'dt_status_modificado',
		'status_modificado_por_ultimo'
	];

	public function cabecalho_ddl()
	{
		return $this->belongsTo(CabecalhoDdl::class, 'id_cabecalho_ddl');
	}

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'id_empresa');
	}

	public function historico_ddls()
	{
		return $this->hasMany(HistoricoDdl::class, 'fk_corpo_ddl');
	}
}
