<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CabecalhoDdl
 * 
 * @property int $id
 * @property string|null $descricao
 * @property string|null $codigo
 * @property string|null $codigo_sem_tags
 * @property Carbon|null $dt_cadastro
 * @property Carbon|null $dt_modificado
 * 
 * @property Collection|CorpoDdl[] $corpo_ddls
 * @property Collection|HistoricoDdl[] $historico_ddls
 *
 * @package App\Models
 */
class CabecalhoDdl extends Model
{
	protected $table = 'cabecalho_ddl';
	public $timestamps = false;
	public $connection = 'system';

	protected $dates = [
		'dt_cadastro',
		'dt_modificado'
	];

	protected $fillable = [
		'descricao',
		'codigo',
		'codigo_sem_tags',
		'dt_cadastro',
		'dt_modificado'
	];

	public function corpo_ddls()
	{
		return $this->hasMany(CorpoDdl::class, 'id_cabecalho_ddl');
	}

	public function historico_ddls()
	{
		return $this->hasMany(HistoricoDdl::class, 'fk_cabecalho_ddl');
	}
}
