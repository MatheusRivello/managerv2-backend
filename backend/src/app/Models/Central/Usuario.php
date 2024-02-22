<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 * 
 * @property int $id
 * @property int|null $fk_empresa
 * @property int|null $fk_perfil
 * @property int $fk_tipo_empresa
 * @property string|null $id_gerente_supervisor
 * @property string $nome
 * @property string|null $telefone
 * @property string|null $email
 * @property string $usuario
 * @property string $senha
 * @property string|null $codigo_autenticacao
 * @property bool $tipo_acesso
 * @property bool|null $status
 * @property bool|null $responsavel
 * @property Carbon|null $codigo_tempo
 * @property string|null $codigo_senha
 * @property Carbon|null $dt_cadastro
 * @property Carbon|null $dt_modificado
 * 
 * @property Empresa|null $empresa
 * @property Perfil|null $perfil
 * @property TipoEmpresa $tipo_empresa
 * @property TokenPassword $token_password
 * @property Collection|Aviso[] $avisos
 * @property Collection|Empresa[] $empresas
 * @property Collection|CabecalhoRequisicaoZip[] $cabecalho_requisicao_zips
 * @property Collection|CercaEletronica[] $cerca_eletronicas
 * @property Collection|ConfiguracaoUsuario[] $configuracao_usuarios
 * @property Collection|HistoricoDdl[] $historico_ddls
 * @property Collection|PedidoAutorizacao[] $pedido_autorizacaos
 *
 * @package App\Models
 */
class Usuario extends Model
{
	protected $table = 'usuario';
	protected $primaryKey = 'id';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_perfil' => 'int',
		'fk_tipo_empresa' => 'int',
		'tipo_acesso' => 'bool',
		'status' => 'bool',
		'responsavel' => 'bool'
	];

	protected $dates = [
		'codigo_tempo',
		'dt_cadastro',
		'dt_modificado'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'fk_empresa',
		'fk_perfil',
		'fk_tipo_empresa',
		'id_gerente_supervisor',
		'nome',
		'telefone',
		'email',
		'usuario',
		'senha',
		'password',
		'remember_token',
		'codigo_autenticacao',
		'tipo_acesso',
		'status',
		'responsavel',
		'codigo_tempo',
		'codigo_senha',
		'dt_cadastro',
		'dt_modificado'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function perfil()
	{
		return $this->belongsTo(Perfil::class, 'fk_perfil');
	}

	public function tipo_empresa()
	{
		return $this->belongsTo(TipoEmpresa::class, 'fk_tipo_empresa');
	}

	public function avisos()
	{
		return $this->belongsToMany(Aviso::class, 'aviso_empresa_usuario', 'fk_usuario', 'fk_aviso')
					->withPivot('fk_empresa', 'qtd_visualizacao');
	}

	public function empresas()
	{
		return $this->hasMany(Empresa::class, 'fk_usuario_responsavel');
	}

	public function cabecalho_requisicao_zips()
	{
		return $this->hasMany(CabecalhoRequisicaoZip::class, 'fk_usuario');
	}

	public function cerca_eletronicas()
	{
		return $this->hasMany(CercaEletronica::class, 'fk_usuario');
	}

	public function configuracao_usuarios()
	{
		return $this->hasMany(ConfiguracaoUsuario::class, 'fk_usuario');
	}

	public function historico_ddls()
	{
		return $this->hasMany(HistoricoDdl::class, 'fk_usuario');
	}

	public function pedido_autorizacaos()
	{
		return $this->hasMany(PedidoAutorizacao::class, 'fk_usuario');
	}

	public function token_password()
	{
		return $this->hasOne(TokenPassword::class, 'id_usuario');
	}
}
