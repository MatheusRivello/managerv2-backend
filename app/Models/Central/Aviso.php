<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Aviso
 * 
 * @property int $id
 * @property int $fk_usuario
 * @property string $titulo
 * @property string|null $descricao
 * @property string|null $caminho_imagem
 * @property string|null $url_imagem
 * @property string|null $url_imagem_thumb
 * @property bool $status
 * @property Carbon|null $dt_inicio
 * @property Carbon|null $dt_fim
 * @property Carbon|null $dt_cadastro
 * @property Carbon|null $dt_modificado
 * @property bool $obrigatorio
 * @property bool|null $exibir_titulo
 * 
 * @property Usuario $usuario
 * @property Collection|Empresa[] $empresas
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Aviso extends Model
{
	protected $table = 'aviso';
	public $connection = 'system';
	public $timestamps = false;

	protected $casts = [
		'fk_usuario' => 'int',
		'status' => 'bool',
		'obrigatorio' => 'bool',
		'exibir_titulo' => 'bool'
	];

	protected $dates = [
		'dt_inicio',
		'dt_fim',
		'dt_cadastro',
		'dt_modificado'
	];

	protected $fillable = [
		'fk_usuario',
		'titulo',
		'descricao',
		'caminho_imagem',
		'url_imagem',
		'url_imagem_thumb',
		'status',
		'dt_inicio',
		'dt_fim',
		'dt_cadastro',
		'dt_modificado',
		'obrigatorio',
		'exibir_titulo'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'fk_usuario');
	}

	public function empresas()
	{
		return $this->belongsToMany(Empresa::class, 'aviso_empresa_usuario', 'fk_aviso', 'fk_empresa')
					->withPivot('fk_usuario', 'qtd_visualizacao');
	}

	public function usuarios()
	{
		return $this->belongsToMany(Usuario::class, 'aviso_empresa_usuario', 'fk_aviso', 'fk_usuario')
					->withPivot('fk_empresa', 'qtd_visualizacao');
	}
}
