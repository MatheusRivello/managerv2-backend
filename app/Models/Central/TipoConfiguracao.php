<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoConfiguracao
 * 
 * @property int $id
 * @property string|null $label
 * @property string $descricao
 * @property string $tipo
 * @property string|null $campo
 * @property string|null $tamanho_maximo
 * @property int $ordem
 * @property string|null $valor_padrao
 * 
 * @property Collection|ConfiguracaoDispositivo[] $configuracao_dispositivos
 * @property Collection|ConfiguracaoDispositivoPadrao[] $configuracao_dispositivo_padraos
 *
 * @package App\Models
 */
class TipoConfiguracao extends Model
{
	protected $table = 'tipo_configuracao';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'ordem' => 'int'
	];

	protected $fillable = [
		'label',
		'descricao',
		'tipo',
		'campo',
		'tamanho_maximo',
		'ordem',
		'valor_padrao'
	];

	public function configuracao_dispositivos()
	{
		return $this->hasMany(ConfiguracaoDispositivo::class, 'fk_tipo_configuracao');
	}

	public function configuracao_dispositivo_padraos()
	{
		return $this->hasMany(ConfiguracaoDispositivoPadrao::class, 'fk_tipo_configuracao');
	}
}
