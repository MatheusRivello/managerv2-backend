<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Produto
 * 
 * @property int $id
 * @property int $id_filial
 * @property int|null $id_fornecedor
 * @property string|null $id_retaguarda
 * @property string $id_grupo
 * @property string|null $id_subgrupo
 * @property int|null $id_grupo_new
 * @property int|null $id_subgrupo_new
 * @property string $descricao
 * @property string|null $cod_barras
 * @property string|null $dun
 * @property string|null $ncm
 * @property string|null $meta_title
 * @property string|null $meta_keywords
 * @property string|null $meta_description
 * @property string|null $descricao_curta
 * @property bool|null $frete_gratis
 * @property int $status
 * @property Carbon|null $pro_inicio
 * @property Carbon|null $pro_fim
 * @property float|null $pro_unitario
 * @property string|null $unidvenda
 * @property string|null $embalagem
 * @property int $qtd_embalagem
 * @property float|null $pro_qtd_estoque
 * @property float|null $pes_bru
 * @property float|null $pes_liq
 * @property float $comprimento
 * @property float $largura
 * @property float $espessura
 * @property string|null $ult_origem
 * @property string|null $ult_uf
 * @property float|null $custo
 * @property float|null $descto_verba
 * @property string|null $aplicacao
 * @property string|null $referencia
 * @property bool|null $tipo_estoque
 * @property Carbon|null $dt_validade
 * @property float|null $multiplo
 * @property bool|null $integra_web
 * @property Carbon|null $dt_alteracao
 * @property int|null $pw_filial
 * @property bool $APRESENTA_VENDA
 * 
 * @property Filial $filial
 * @property Fornecedor|null $fornecedor
 * @property Grupo|null $grupo
 * @property Subgrupo|null $subgrupo
 * @property Collection|ClienteCashback[] $cliente_cashbacks
 * @property Collection|MixProduto[] $mix_produtos
 * @property NotaFiscalItem $nota_fiscal_item
 * @property Collection|PedidoItem[] $pedido_items
 * @property Collection|ProdutoCashback[] $produto_cashbacks
 * @property Collection|ProdutoDesctoQtd[] $produto_descto_qtds
 * @property Collection|ProdutoEmbalagem[] $produto_embalagems
 * @property Collection|ProdutoEstoque[] $produto_estoques
 * @property Collection|ProdutoImagem[] $produto_imagems
 * @property Collection|ProdutoIpi[] $produto_ipis
 * @property Collection|ProdutoSt[] $produto_sts
 * @property Collection|ProtabelaIten[] $protabela_itens
 * @property Collection|VendaPlanoProduto[] $venda_plano_produtos
 * @property Collection|Vendedor[] $vendedors
 *
 * @package App\Models
 */
class Produto extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'produto';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'id_fornecedor' => 'int',
		'id_grupo_new' => 'int',
		'id_subgrupo_new' => 'int',
		'frete_gratis' => 'bool',
		'status' => 'int',
		'pro_unitario' => 'float',
		'qtd_embalagem' => 'int',
		'pro_qtd_estoque' => 'float',
		'pes_bru' => 'float',
		'pes_liq' => 'float',
		'comprimento' => 'float',
		'largura' => 'float',
		'espessura' => 'float',
		'custo' => 'float',
		'descto_verba' => 'float',
		'multiplo' => 'float',
		'integra_web' => 'bool',
		'pw_filial' => 'int',
		'APRESENTA_VENDA' => 'bool'
	];

	protected $dates = [
		'pro_inicio',
		'pro_fim',
		'dt_validade',
		'dt_alteracao'
	];

	protected $fillable = [
		'id_filial',
		'id_fornecedor',
		'id_retaguarda',
		'id_grupo',
		'id_subgrupo',
		'id_grupo_new',
		'id_subgrupo_new',
		'descricao',
		'cod_barras',
		'dun',
		'ncm',
		'meta_title',
		'meta_keywords',
		'meta_description',
		'descricao_curta',
		'frete_gratis',
		'status',
		'pro_inicio',
		'pro_fim',
		'pro_unitario',
		'unidvenda',
		'embalagem',
		'qtd_embalagem',
		'pro_qtd_estoque',
		'pes_bru',
		'pes_liq',
		'comprimento',
		'largura',
		'espessura',
		'ult_origem',
		'ult_uf',
		'custo',
		'descto_verba',
		'aplicacao',
		'referencia',
		'tipo_estoque',
		'dt_validade',
		'multiplo',
		'integra_web',
		'dt_alteracao',
		'pw_filial',
		'APRESENTA_VENDA'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function fornecedor()
	{
		return $this->belongsTo(Fornecedor::class, 'id_fornecedor');
	}

	public function grupo()
	{
		return $this->belongsTo(Grupo::class, 'id_grupo_new');
	}

	public function subgrupo()
	{
		return $this->belongsTo(Subgrupo::class, 'id_subgrupo_new');
	}

	public function cliente_cashbacks()
	{
		return $this->hasMany(ClienteCashback::class, 'id_produto');
	}

	public function mix_produtos()
	{
		return $this->hasMany(MixProduto::class, 'id_produto');
	}

	public function nota_fiscal_item()
	{
		return $this->hasOne(NotaFiscalItem::class, 'id_produto');
	}

	public function pedido_items()
	{
		return $this->hasMany(PedidoItem::class, 'id_produto');
	}

	public function produto_cashbacks()
	{
		return $this->hasMany(ProdutoCashback::class, 'id_produto');
	}

	public function produto_descto_qtds()
	{
		return $this->hasMany(ProdutoDesctoQtd::class, 'id_produto');
	}

	public function produto_embalagems()
	{
		return $this->hasMany(ProdutoEmbalagem::class, 'id_produto');
	}

	public function produto_estoques()
	{
		return $this->hasMany(ProdutoEstoque::class, 'id_produto');
	}

	public function produto_imagems()
	{
		return $this->hasMany(ProdutoImagem::class, 'id_produto');
	}

	public function produto_ipis()
	{
		return $this->hasMany(ProdutoIpi::class, 'id_produto');
	}

	public function produto_sts()
	{
		return $this->hasMany(ProdutoSt::class, 'id_produto');
	}

	public function protabela_itens()
	{
		return $this->hasMany(ProtabelaIten::class, 'id_produto');
	}

	public function venda_plano_produtos()
	{
		return $this->hasMany(VendaPlanoProduto::class, 'id_produto');
	}
	public function status_produto(){
		return $this->belongsto(StatusProduto::class);
	}

	public function vendedors()
	{
		return $this->belongsToMany(Vendedor::class, 'vendedor_produto', 'id_produto', 'id_vendedor');
	}

	public function getRelacionamentosCount(){
		$soma=$this->cliente_cashbacks()->count()+
		$this->mix_produtos()->count()+
		$this->nota_fiscal_item()->count()+
		$this->pedido_items()->count()+
		$this->produto_cashbacks()->count()+
		$this->produto_descto_qtds()->count()+
		$this->produto_embalagems()->count()+
		$this->produto_estoques()->count()+
		$this->produto_imagems()->count()+
		$this->produto_ipis()->count()+
		$this->produto_sts()->count()+
		$this->protabela_itens()->count()+
		$this->venda_plano_produtos()->count();

	}
}
