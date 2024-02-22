<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwQtdLicencasEmpresa
 * 
 * @property int|null $id
 * @property string|null $empresa
 * @property int|null $qtd_contratado
 * @property int|null $qtd_em_uso
 *
 * @package App\Models
 */
class VwQtdLicencasEmpresa extends Model
{
	protected $table = 'vw_qtd_licencas_empresas';
	protected $connection = 'system';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'qtd_contratado' => 'int',
		'qtd_em_uso' => 'int'
	];

	protected $fillable = [
		'id',
		'empresa',
		'qtd_contratado',
		'qtd_em_uso'
	];
}
