<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AvisoEmpresaUsuario
 * 
 * @property int $fk_aviso
 * @property int $fk_empresa
 * @property int|null $fk_usuario
 * @property int|null $qtd_visualizacao
 * 
 * @property Aviso $aviso
 * @property Empresa $empresa
 * @property Usuario|null $usuario
 *
 * @package App\Models
 */
class AvisoEmpresaUsuario extends Model
{
	protected $table = 'aviso_empresa_usuario';	
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_aviso' => 'int',
		'fk_empresa' => 'int',
		'fk_usuario' => 'int',
		'qtd_visualizacao' => 'int'
	];

	protected $fillable = [
		'fk_usuario',
		'qtd_visualizacao'
	];

	public function aviso()
	{
		return $this->belongsTo(Aviso::class, 'fk_aviso');
	}

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'fk_usuario');
	}
}
