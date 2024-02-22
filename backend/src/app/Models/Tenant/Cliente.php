<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 * /**
 * @OA\Schema 
 * Propriedades do Cliente
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_filial", type="integer"),
 * @OA\Property(property="id_retaguarda", type="string"),
 * @OA\Property(property="id_tabela_preco", type="string"),
 * @OA\Property(property="id_prazo_pgto", type="string"),
 * @OA\Property(property="id_forma_pgto", type="string"),
 * @OA\Property(property="id_status", type="string"),
 * @OA\Property(property="razao_social", type="string"),
 * @OA\Property(property="nome_fantasia", type="string"),
 * @OA\Property(property="cnpj", type="string"),
 * @OA\Property(property="senha", type="integer"),
 * @OA\Property(property="email", type="string"),
 * @OA\Property(property="codigo_tempo", type="datetime"),
 * @OA\Property(property="codigo_senha", type="string"),
 * @OA\Property(property="id_atividade", type="string"),
 * @OA\Property(property="telefone", type="string"),
 * @OA\Property(property="tipo", type="string"),
 * @OA\Property(property="tipo_contribuinte", type="string"),
 * @OA\Property(property="site", type="string"),
 * @OA\Property(property="email_nfe", type="string"),
 * @OA\Property(property="limite_credito", type="double"),
 * @OA\Property(property="saldo", type="double"),
 * @OA\Property(property="saldo_credor", type="double"),
 * @OA\Property(property="sinc_erp", type="integer"),
 * @OA\Property(property="observacao", type="string"),
 * @OA\Property(property="intervalo_visita", type="string"),
 * @OA\Property(property="dt_ultima_visita", type="datetime"),
 * @OA\Property(property="dt_cadastro", type="datetime"),
 * @OA\Property(property="dt_modificado", type="datetime"),
 * @OA\Property(property="bloqueia_forma_pgto", type="integer"),
 * @OA\Property(property="bloqueia_prazo_pgto", type="integer"),
 * @OA\Property(property="bloqueia_tabela", type="integer"),
 * @OA\Property(property="id_mobile", type="integer"),
 * @OA\Property(property="inscricao_municipal", type="string"),
 * @OA\Property(property="inscricao_rg", type="string"),
 * @OA\Property(property="ven_cod", type="integer"),
 * @OA\Property(property="integra_web", type="integer"),
 * @OA\Property(property="atraso_tot", type="double"),
 * @OA\Property(property="avencer", type="double"),
 * @OA\Property(property="media_dias_atraso", type="integer"),
 * @OA\Property(property="dt_ultima_compra", type="date")
 * 
 * @property int $id
 * @property int $id_filial
 * @property string|null $id_retaguarda
 * @property string|null $id_tabela_preco
 * @property string|null $id_prazo_pgto
 * @property string|null $id_forma_pgto
 * @property int|null $id_status
 * @property string|null $razao_social
 * @property string|null $nome_fantasia
 * @property string $cnpj
 * @property string|null $senha
 * @property string|null $email
 * @property Carbon|null $codigo_tempo
 * @property string|null $codigo_senha
 * @property string|null $id_atividade
 * @property string|null $telefone
 * @property string|null $tipo
 * @property string|null $tipo_contribuinte
 * @property string|null $site
 * @property string|null $email_nfe
 * @property float|null $limite_credito
 * @property float|null $saldo
 * @property float $saldo_credor
 * @property bool|null $sinc_erp
 * @property string|null $observacao
 * @property string|null $intervalo_visita
 * @property Carbon|null $dt_ultima_visita
 * @property Carbon|null $dt_cadastro
 * @property Carbon|null $dt_modificado
 * @property bool|null $bloqueia_forma_pgto
 * @property bool|null $bloqueia_prazo_pgto
 * @property bool|null $bloqueia_tabela
 * @property int|null $id_mobile
 * @property string|null $inscricao_municipal
 * @property string|null $inscricao_rg
 * @property int|null $ven_cod
 * @property int|null $integra_web
 * @property float $atraso_tot
 * @property float $avencer
 * @property int|null $media_dias_atraso
 * @property Carbon|null $dt_ultima_compra
 * 
 * @property Filial $filial
 * @property StatusCliente|null $status_cliente
 * @property Collection|ClienteCashback[] $cliente_cashbacks
 * @property Collection|ClienteFormaPgto[] $cliente_forma_pgtos
 * @property Collection|ClientePrazoPgto[] $cliente_prazo_pgtos
 * @property Collection|ClienteReferencium[] $cliente_referencia
 * @property Collection|ClienteTabelaGrupo[] $cliente_tabela_grupos
 * @property Collection|Contato[] $contatos
 * @property Collection|Endereco[] $enderecos
 * @property Collection|MixProduto[] $mix_produtos
 * @property Collection|NotaFiscal[] $nota_fiscals
 * @property Collection|Pedido[] $pedidos
 * @property Collection|TituloFinanceiro[] $titulo_financeiros
 * @property Collection|VendaPlano[] $venda_planos
 * @property Collection|VendaPlanoProduto[] $venda_plano_produtos
 * @property Collection|Vendedor[] $vendedors
 * @property Collection|Visita[] $visita
 *
 * @package App\Models
 */
class Cliente extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'cliente';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'id_status' => 'int',
		'limite_credito' => 'float',
		'saldo' => 'float',
		'saldo_credor' => 'float',
		'bloqueia_forma_pgto' => 'bool',
		'bloqueia_prazo_pgto' => 'bool',
		'bloqueia_tabela' => 'bool',
		'id_mobile' => 'int',
		'ven_cod' => 'int',
		'integra_web' => 'int',
		'atraso_tot' => 'float',
		'avencer' => 'float',
		'media_dias_atraso' => 'int'
	];

	protected $dates = [
		'codigo_tempo',
		'dt_ultima_visita',
		'dt_cadastro',
		'dt_modificado',
		'dt_ultima_compra'
	];

	protected $fillable = [
		'id',
		'id_filial',
		'id_retaguarda',
		'id_tabela_preco',
		'id_prazo_pgto',
		'id_forma_pgto',
		'id_status',
		'razao_social',
		'nome_fantasia',
		'cnpj',
		'senha',
		'email',
		'codigo_tempo',
		'codigo_senha',
		'id_atividade',
		'telefone',
		'tipo',
		'tipo_contribuinte',
		'site',
		'email_nfe',
		'limite_credito',
		'saldo',
		'saldo_credor',
		'sinc_erp',
		'observacao',
		'intervalo_visita',
		'dt_ultima_visita',
		'dt_cadastro',
		'dt_modificado',
		'bloqueia_forma_pgto',
		'bloqueia_prazo_pgto',
		'bloqueia_tabela',
		'id_mobile',
		'inscricao_municipal',
		'inscricao_rg',
		'ven_cod',
		'integra_web',
		'atraso_tot',
		'avencer',
		'media_dias_atraso',
		'dt_ultima_compra'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function status_cliente()
	{
		return $this->belongsTo(StatusCliente::class, 'id_status');
	}

	public function cliente_cashbacks()
	{
		return $this->hasMany(ClienteCashback::class, 'id_cliente');
	}

	public function cliente_forma_pgtos()
	{
		return $this->hasMany(ClienteFormaPgto::class, 'id_cliente');
	}

	public function cliente_prazo_pgtos()
	{
		return $this->hasMany(ClientePrazoPgto::class, 'id_cliente');
	}

	public function cliente_referencia()
	{
		return $this->hasMany(ClienteReferencia::class, 'id_cliente');
	}

	public function cliente_tabela_grupos()
	{
		return $this->hasMany(ClienteTabelaGrupo::class, 'id_cliente');
	}

	public function contatos()
	{
		return $this->hasMany(Contato::class, 'id_cliente');
	}

	public function enderecos()
	{
		return $this->hasMany(Endereco::class, 'id_cliente');
	}

	public function mix_produtos()
	{
		return $this->hasMany(MixProduto::class, 'id_cliente');
	}

	public function nota_fiscals()
	{
		return $this->hasMany(NotaFiscal::class, 'id_cliente');
	}

	public function pedidos()
	{
		return $this->hasMany(Pedido::class, 'id_cliente');
	}

	public function titulo_financeiros()
	{
		return $this->hasMany(TituloFinanceiro::class, 'id_cliente');
	}

	public function venda_planos()
	{
		return $this->hasMany(VendaPlano::class, 'id_cliente');
	}

	public function venda_plano_produtos()
	{
		return $this->hasMany(VendaPlanoProduto::class, 'id_cliente');
	}

	public function vendedors()
	{
		return $this->belongsToMany(Vendedor::class, 'vendedor_cliente', 'id_cliente', 'id_vendedor');
	}

	public function visita()
	{
		return $this->hasMany(Visita::class, 'id_cliente');
	}
	public function getRelacionamentosCount(){
		
		$soma=$this->cliente_forma_pgtos()->count()+
		$this->cliente_prazo_pgtos()->count()+
		$this->cliente_referencia()->count()+
		$this->cliente_tabela_grupos()->count()+
		$this->contatos()->count()+
		$this->enderecos()->count()+
		$this->mix_produtos()->count()+
		$this->nota_fiscals()->count()+
		$this->pedidos()->count()+
		$this->titulo_financeiros()->count()+
		$this->venda_planos()->count()+
		$this->venda_plano_produtos()->count()+
		$this->visita()->count();

		return $soma;
	}
}
