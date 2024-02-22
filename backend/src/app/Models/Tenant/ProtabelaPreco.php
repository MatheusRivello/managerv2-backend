<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProtabelaPreco
 * @OA\Schema 
 * Propriedades da Tabela de Preço
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="idFilial", type="integer"),
 * @OA\Property(property="idRetaguarda", type="string"),
 * @OA\Property(property="tabDesc", type="string"),
 * @OA\Property(property="tabIni", type="string"),
 * @OA\Property(property="tabFim", type="string"),
 * @OA\Property(property="gerarVerba", type="boolean")
 * @property int $id
 * @property int $id_filial
 * @property string $id_retaguarda
 * @property string $tab_desc
 * @property Carbon|null $tab_ini
 * @property Carbon|null $tab_fim
 * @property bool|null $gerar_verba
 * 
 * @property Filial $filial
 * @property Collection|Pedido[] $pedidos
 * @property Collection|PedidoItem[] $pedido_items
 * @property Collection|ProdutoDesctoQtd[] $produto_descto_qtds
 * @property Collection|ProtabelaIten[] $protabela_itens
 * @property Collection|VendedorProtabelapreco[] $vendedor_protabelaprecos
 *
 * @package App\Models
 */
class ProtabelaPreco extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'protabela_preco';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'gerar_verba' => 'bool'
	];

	protected $dates = [
		'tab_ini',
		'tab_fim'
	];

	protected $fillable = [
		'id_filial',
		'id_retaguarda',
		'tab_desc',
		'tab_ini',
		'tab_fim',
		'gerar_verba'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function pedidos()
	{
		return $this->hasMany(Pedido::class, 'id_tabela');
	}

	public function pedido_items()
	{
		return $this->hasMany(PedidoItem::class, 'id_tabela');
	}

	public function produto_descto_qtds()
	{
		return $this->hasMany(ProdutoDesctoQtd::class, 'id_protabela_preco');
	}

	public function protabela_itens()
	{
		return $this->hasMany(ProtabelaIten::class, 'id_protabela_preco');
	}

	public function vendedor_protabelaprecos()
	{
		return $this->hasMany(VendedorProtabelapreco::class, 'id_protabela_preco');
	}

	public function getRelacionamentosCount()
	{
		$soma = $this->filial()->count() +
			$this->pedidos()->count() +
			$this->pedido_items()->count() +
			$this->produto_descto_qtds()->count() +
			$this->protabela_itens()->count() +
			$this->vendedor_protabelaprecos()->count();

		return $soma;
	}
}
