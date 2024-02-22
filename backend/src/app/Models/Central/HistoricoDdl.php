<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HistoricoDdl
 * 
 * @property int $id
 * @property bool $tipo
 * @property string $descricao_view
 * @property int|null $fk_cabecalho_ddl
 * @property int|null $fk_corpo_ddl
 * @property int $fk_usuario
 * @property string $tabela
 * @property string $mensagem
 * @property string|null $conteudo
 * @property string|null $conteudo_anterior
 * @property Carbon $dt_cadastro
 * 
 * @property CabecalhoDdl|null $cabecalho_ddl
 * @property CorpoDdl|null $corpo_ddl
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class HistoricoDdl extends Model
{
	protected $table = 'historico_ddl';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'tipo' => 'bool',
		'fk_cabecalho_ddl' => 'int',
		'fk_corpo_ddl' => 'int',
		'fk_usuario' => 'int'
	];

	protected $dates = [
		'dt_cadastro'
	];

	protected $fillable = [
		'tipo',
		'descricao_view',
		'fk_cabecalho_ddl',
		'fk_corpo_ddl',
		'fk_usuario',
		'tabela',
		'mensagem',
		'conteudo',
		'conteudo_anterior',
		'dt_cadastro'
	];

	public function cabecalho_ddl()
	{
		return $this->belongsTo(CabecalhoDdl::class, 'fk_cabecalho_ddl');
	}

	public function corpo_ddl()
	{
		return $this->belongsTo(CorpoDdl::class, 'fk_corpo_ddl');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'fk_usuario');
	}
}
