<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Empresa
 * 
 * @property int $id
 * @property int|null $fk_usuario_responsavel
 * @property string $razao_social
 * @property string|null $nome_fantasia
 * @property string|null $codigo_autenticacao
 * @property string|null $cnpj
 * @property string|null $email
 * @property int|null $qtd_licenca
 * @property int|null $qtd_licenca_utilizada
 * @property string|null $contato
 * @property string|null $telefone1
 * @property string|null $telefone2
 * @property string|null $observacao
 * @property bool|null $usa_pw
 * @property bool|null $pw_status
 * @property string|null $pw_dominio
 * @property string|null $bd_host
 * @property string|null $bd_porta
 * @property string|null $bd_usuario
 * @property string|null $bd_senha
 * @property string|null $bd_nome
 * @property bool|null $bd_ssl
 * @property string|null $ip
 * @property Carbon|null $dt_ultimo_login
 * @property bool $status
 * @property Carbon|null $dt_cadastro
 * @property Carbon|null $dt_modificado
 * @property bool|null $atualizar_sincronizador
 * @property string|null $versao_sincronizador
 * @property Carbon|null $dt_versao_sincronizador_atualizado
 * @property int|null $pw_filial
 * @property string|null $pw_nome
 * @property string|null $pw_logo
 * @property string|null $pw_termo
 * @property bool|null $atualizada
 * 
 * @property Usuario|null $usuario
 * @property Collection|Aviso[] $avisos
 * @property Collection|Usuario[] $usuarios
 * @property Collection|CabecalhoRequisicaoZip[] $cabecalho_requisicao_zips
 * @property Collection|CercaEletronica[] $cerca_eletronicas
 * @property Collection|ConfiguracaoDispositivo[] $configuracao_dispositivos
 * @property Collection|ConfiguracaoDispositivoPadrao[] $configuracao_dispositivo_padraos
 * @property Collection|ConfiguracaoEmpresa[] $configuracao_empresas
 * @property Collection|CorpoDdl[] $corpo_ddls
 * @property Collection|Dispositivo[] $dispositivos
 * @property Collection|HorarioUtilizacaoDispositivo[] $horario_utilizacao_dispositivos
 * @property Collection|HorarioUtilizacaoDispositivoPadrao[] $horario_utilizacao_dispositivo_padraos
 * @property Collection|PedidoAutorizacao[] $pedido_autorizacaos
 * @property Collection|PedidosNovosEmail[] $pedidos_novos_emails
 * @property Collection|Perfil[] $perfils
 * @property PeriodoSincronizacao $periodo_sincronizacao
 * @property Collection|ServicoLocal[] $servico_locals
 * @property SincronismoLog $sincronismo_log
 * @property Collection|TotalizadorDb[] $totalizador_dbs
 * @property Collection|TotalizadorEmpresa[] $totalizador_empresas
 *
 * @package App\Models
 */
class Empresa extends Model
{
	protected $table = 'empresa';
	protected $connection = 'system';
	public $timestamps = false;

	protected $casts = [
		'fk_usuario_responsavel' => 'int',
		'qtd_licenca' => 'int',
		'qtd_licenca_utilizada' => 'int',
		'usa_pw' => 'bool',
		'pw_status' => 'bool',
		'bd_ssl' => 'bool',
		'status' => 'bool',
		'atualizar_sincronizador' => 'bool',
		'pw_filial' => 'int',
		'atualizada' => 'bool'
	];

	protected $dates = [
		'dt_ultimo_login',
		'dt_cadastro',
		'dt_modificado',
		'dt_versao_sincronizador_atualizado'
	];

	protected $fillable = [
		'fk_usuario_responsavel',
		'razao_social',
		'nome_fantasia',
		'codigo_autenticacao',
		'cnpj',
		'email',
		'qtd_licenca',
		'qtd_licenca_utilizada',
		'contato',
		'telefone1',
		'telefone2',
		'observacao',
		'usa_pw',
		'pw_status',
		'pw_dominio',
		'bd_host',
		'bd_porta',
		'bd_usuario',
		'bd_senha',
		'bd_nome',
		'bd_ssl',
		'ip',
		'dt_ultimo_login',
		'status',
		'dt_cadastro',
		'dt_modificado',
		'atualizar_sincronizador',
		'versao_sincronizador',
		'dt_versao_sincronizador_atualizado',
		'pw_filial',
		'pw_nome',
		'pw_logo',
		'pw_termo',
		'atualizada'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'fk_usuario_responsavel');
	}

	public function avisos()
	{
		return $this->belongsToMany(Aviso::class, 'aviso_empresa_usuario', 'fk_empresa', 'fk_aviso')
					->withPivot('fk_usuario', 'qtd_visualizacao');
	}

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'fk_empresa');
	}

	public function cabecalho_requisicao_zips()
	{
		return $this->hasMany(CabecalhoRequisicaoZip::class, 'fk_empresa');
	}

	public function cerca_eletronicas()
	{
		return $this->hasMany(CercaEletronica::class, 'fk_empresa');
	}

	public function configuracao_dispositivos()
	{
		return $this->hasMany(ConfiguracaoDispositivo::class, 'fk_empresa');
	}

	public function configuracao_dispositivo_padraos()
	{
		return $this->hasMany(ConfiguracaoDispositivoPadrao::class, 'fk_empresa');
	}

	public function configuracao_empresas()
	{
		return $this->hasMany(ConfiguracaoEmpresa::class, 'fk_empresa');
	}

	public function corpo_ddls()
	{
		return $this->hasMany(CorpoDdl::class, 'id_empresa');
	}

	public function dispositivos()
	{
		return $this->hasMany(Dispositivo::class, 'fk_empresa');
	}

	public function horario_utilizacao_dispositivos()
	{
		return $this->hasMany(HorarioUtilizacaoDispositivo::class, 'fk_empresa');
	}

	public function horario_utilizacao_dispositivo_padraos()
	{
		return $this->hasMany(HorarioUtilizacaoDispositivoPadrao::class, 'fk_empresa');
	}

	public function pedido_autorizacaos()
	{
		return $this->hasMany(PedidoAutorizacao::class, 'fk_empresa');
	}

	public function pedidos_novos_emails()
	{
		return $this->hasMany(PedidosNovosEmail::class, 'fk_empresa');
	}

	public function perfils()
	{
		return $this->hasMany(Perfil::class, 'fk_empresa');
	}

	public function periodo_sincronizacao()
	{
		return $this->hasOne(PeriodoSincronizacao::class, 'fk_empresa');
	}

	public function servico_locals()
	{
		return $this->hasMany(ServicoLocal::class, 'fk_empresa');
	}

	public function sincronismo_log()
	{
		return $this->hasOne(SincronismoLog::class, 'id_empresa');
	}

	public function totalizador_dbs()
	{
		return $this->hasMany(TotalizadorDb::class, 'fk_empresa');
	}

	public function totalizador_empresas()
	{
		return $this->hasMany(TotalizadorEmpresa::class, 'fk_empresa');
	}

	public function grupo_relatorios()
	{
		return $this->hasMany(GrupoRelatorio::class, 'id_empresa');
	}
}
