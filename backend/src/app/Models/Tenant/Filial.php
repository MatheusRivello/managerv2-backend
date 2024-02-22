<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Filial
 *  * @OA\Schema 
 * Propriedades da Filial
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="emp_cgc", type="string"),
 * @OA\Property(property="emp_raz", type="string"),
 * @OA\Property(property="emp_fan", type="string"),
 * @OA\Property(property="emp_ativa", type="integer"),
 * @OA\Property(property="emp_uf", type="string"),
 * @OA\Property(property="emp_caminho_img", type="string"),
 * @OA\Property(property="emp_url_img", type="string"),
 * @OA\Property(property="emp_email", type="string")
 * 
 * @property int $id
 * @property string|null $emp_cgc
 * @property string|null $emp_raz
 * @property string|null $emp_fan
 * @property bool $emp_ativa
 * @property string|null $emp_uf
 * @property string|null $emp_caminho_img
 * @property string|null $emp_url_img
 * @property string $emp_email
 * 
 * @property Collection|Atividade[] $atividades
 * @property Collection|Aviso[] $avisos
 * @property Collection|Cliente[] $clientes
 * @property Collection|ConfiguracaoFilial[] $configuracao_filials
 * @property Collection|Fornecedor[] $fornecedors
 * @property Collection|Grupo[] $grupos
 * @property Collection|IndicadorMargem[] $indicador_margems
 * @property Collection|Log[] $logs
 * @property Collection|Meta[] $meta
 * @property Collection|Motivo[] $motivos
 * @property Collection|NotaFiscal[] $nota_fiscals
 * @property Collection|Pedido[] $pedidos
 * @property Collection|Produto[] $produtos
 * @property Collection|ProtabelaPreco[] $protabela_precos
 * @property Collection|StatusPedido[] $status_pedidos
 * @property Collection|Subgrupo[] $subgrupos
 * @property Collection|VendaPlano[] $venda_planos
 * @property Collection|VendaPlanoProduto[] $venda_plano_produtos
 * @property Collection|VendedorPrazo[] $vendedor_prazos
 * @property Collection|Visita[] $visita
 *
 * @package App\Models
 */
class Filial extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'filial';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'emp_ativa' => 'int'
	];

	protected $fillable = [
		'id',
		'emp_ativa',
		'emp_cgc',
		'emp_raz',
		'emp_fan',
		'emp_ativa',
		'emp_uf',
		'emp_caminho_img',
		'emp_url_img',
		'emp_email'
	];

	public function atividades()
	{
		return $this->hasMany(Atividade::class, 'id_filial');
	}

	public function avisos()
	{
		return $this->hasMany(Aviso::class, 'id_filial');
	}

	public function clientes()
	{
		return $this->hasMany(Cliente::class, 'id_filial');
	}

	public function configuracao_filials()
	{
		return $this->hasMany(ConfiguracaoFilial::class, 'id_filial');
	}

	public function fornecedors()
	{
		return $this->hasMany(Fornecedor::class, 'id_filial');
	}

	public function grupos()
	{
		return $this->hasMany(Grupo::class, 'id_filial');
	}

	public function indicador_margems()
	{
		return $this->hasMany(IndicadorMargem::class, 'id_filial');
	}

	public function logs()
	{
		return $this->hasMany(Log::class, 'id_filial');
	}

	public function meta()
	{
		return $this->hasMany(Meta::class, 'id_filial');
	}

	public function motivos()
	{
		return $this->hasMany(Motivo::class, 'id_filial');
	}

	public function nota_fiscals()
	{
		return $this->hasMany(NotaFiscal::class, 'id_filial');
	}

	public function pedidos()
	{
		return $this->hasMany(Pedido::class, 'id_filial');
	}

	public function produtos()
	{
		return $this->hasMany(Produto::class, 'id_filial');
	}

	public function protabela_precos()
	{
		return $this->hasMany(ProtabelaPreco::class, 'id_filial');
	}

	public function status_pedidos()
	{
		return $this->hasMany(StatusPedido::class, 'id_filial');
	}

	public function subgrupos()
	{
		return $this->hasMany(Subgrupo::class, 'id_filial');
	}

	public function venda_planos()
	{
		return $this->hasMany(VendaPlano::class, 'id_filial');
	}

	public function venda_plano_produtos()
	{
		return $this->hasMany(VendaPlanoProduto::class, 'id_filial');
	}

	public function vendedor_prazos()
	{
		return $this->hasMany(VendedorPrazo::class, 'id_filial');
	}

	public function visita()
	{
		return $this->hasMany(Visita::class, 'id_filial');
	}

	public function Rota()
	{
		return $this->hasMany(Rastro::class,'id_filial');
	}

	public function getRelacionamentosCount(){
		
		$soma= $this->atividades()->count()+
		$this->avisos()->count()+
		$this->clientes()->count()+
		$this->configuracao_filials()->count()+
		$this->fornecedors()->count()+
		$this->grupos()->count()+
		$this->indicador_margems()->count()+
		$this->logs()->count()+
		$this->meta()->count()+
	    $this->nota_fiscals()->count()+
		$this->pedidos()->count()+
		$this->produtos()->count()+
		$this->protabela_precos()->count()+
		$this->status_pedidos()->count()+
		$this->subgrupos()->count()+
		$this->venda_plano_produtos()->count()+
		$this->vendedor_prazos()->count()+
		$this->visita()->count();

		return $soma;
	}
}
