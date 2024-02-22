<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use App\Models\Tenant\Vendedor;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CercaEletronica
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property int $fk_usuario
 * @property int $fk_motivo_cerca_eletronica
 * @property string $dt_cadastro
 * @property int|null $id_vendedor
 * @property string $token
 * @property string|null $mac
 * @property string $tipo_gerado
 * @property string|null $observacao
 * 
 * @property MotivoCercaEletronica $motivo_cerca_eletronica
 * @property Empresa $empresa
 * @property Vendedor $vendedor
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class CercaEletronica extends Model
{
	protected $table = 'cerca_eletronica';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_usuario' => 'int',
		'fk_motivo_cerca_eletronica' => 'int',
		'id_vendedor' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'fk_empresa',
		'fk_usuario',
		'fk_motivo_cerca_eletronica',
		'dt_cadastro',
		'id_vendedor',
		'token',
		'mac',
		'tipo_gerado',
		'observacao'
	];

	public function motivo_cerca_eletronica()
	{
		return $this->belongsTo(MotivoCercaEletronica::class, 'fk_motivo_cerca_eletronica');
	}

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'fk_usuario');
	}

	public function vendedor()
	{
		return $this->setConnection(\App\Services\Models\ConexaoTenantService::definirConexaoRelacionamento())->belongsTo(Vendedor::class, 'id_vendedor');
	}
}
