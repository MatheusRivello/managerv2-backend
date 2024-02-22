<?php

namespace App\Models;

use App\Models\Central\Perfil;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
	public $timestamps = false;

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
    
    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
