<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GrupoRelatorio
 * 
 * @property int $id
 * @property int|null $id_empresa
 * @property string $descricao
 * @property bool $status
 * 
 * @property Empresa|null $empresa
 * @property Collection|Relatorio[] $relatorios
 *
 * @package App\Models
 */
class GrupoRelatorio extends Model
{
	protected $table = 'grupo_relatorio';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id_empresa' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_empresa',
		'descricao',
		'status'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'id_empresa');
	}

	public function relatorios()
	{
		return $this->hasMany(Relatorio::class, 'id_grupo');
	}

	public function getRelatoriosCount()
    {
        return $this->relatorios()->count();
    }
}
