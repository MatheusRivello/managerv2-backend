<?php

namespace App\Http\Controllers\mobile\v1\zip;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Central\PeriodoSincronizacao;
use App\Models\Tenant\Campanha;
use App\Models\Tenant\CampanhaBeneficio;
use App\Models\Tenant\CampanhaModalidade;
use App\Models\Tenant\CampanhaRequisito;
use App\Models\Tenant\Produto;
use App\Models\Tenant\ProdutoDesctoQtd;
use App\Models\Tenant\ProtabelaIten;
use App\Models\Tenant\ProtabelaPreco;
use App\Models\Tenant\VendedorProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoMobileController extends BaseMobileController
{
    protected $className;
    protected $restricao_protabela_preco = FALSE;

    public function __construct()
    {
        $this->className = "Produto";

        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    /**
     *Método principal para retornar todos os dados, seu retorno é via zip
     */
    public function completo()
    {
        if ($this->periodoSincronizacao()->restricao_protabela_preco == 1) {
            $this->restricao_protabela_preco = TRUE;
        }

        $data = array(
            "produto.json" => $this->_produto(),
            "grupo.json" => $this->_grupo(),
            "subgrupo.json" => $this->_subGrupo(),
            "produtodesctoqtd.json" => $this->_produtoDesctoQtd(),
            "statusproduto.json" => $this->_statusProduto(),
            "produtoipi.json" => $this->_ipi(),
            "produtost.json" => $this->_st(),
            "tabelapreco.json" => $this->_tabelapreco(),
            "tabelaprecoitem.json" => $this->_tabelaprecoitem(),
            "mixproduto.json" => $this->_mixproduto(),
            "fornecedor.json" => $this->_fornecedor(),
            "produtoembalagem.json" => $this->_produtoEmbalagem(),
            "produtoestoque.json" => $this->_produtoEstoque(),
            "campanha.json" => $this->_campanha(),
            "campanha_beneficio.json" => NULL,
            "campanha_requisito.json" => NULL,
            "campanha_modalidade.json" => NULL
        );

        if (!is_null($data['campanha.json'])) {
            $data["campanha_beneficio.json"] = $this->_campanhaBeneficio();
            $data["campanha_requisito.json"] = $this->_campanhaRequisito();
            $data["campanha_modalidade.json"] = $this->_campanhaModalidade();
        }

        $this->_downloadZip($this->className, $data);
    }

    /**
     *Retorna o estoque dos produtos
     */
    public function estoque(Request $request)
    {
        $dtModificado = $request->dtModificado;
        if ($dtModificado == null || $dtModificado == "") exit("Envie a data via parâmetro.");

        $dados = $this->_produtoEstoque($dtModificado);

        if ($dados == NULL) {
            return response()->json(NULL, HTTP_NO_CONTENT);
        } else {
            $data = array(
                "produtoestoque.json" => $dados
            );
            $this->_downloadZip("estoque", $data);
        }
    }

    public function _campanhaBeneficio()
    {
        $data = NULL;

        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_CAMPANHA_BENEFICIO)) {
            $data = $this->getCampanhaBeneficioVendedor($this->usuarioLogado()->id_vendedor);
        }

        return $this->_verificarData($data);
    }

    public function _campanhaRequisito()
    {
        $data = NULL;

        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_CAMPANHA_REQUISITO)) {
            $data = $this->getCampanhaRequisitoVendedor($this->usuarioLogado()->id_vendedor);
        }

        return $this->_verificarData($data);
    }

    public function _campanhaModalidade()
    {
        $data = NULL;

        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_CAMPANHA_MODALIDADE)) {
            $data = $this->getCampanhaModalidadeVendedor($this->usuarioLogado()->id_vendedor);
        }

        return $this->_verificarData($data);
    }

    public function _campanha()
    {
        $data = NULL;

        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_CAMPANHA)) {
            $data = $this->getCampanhaVendedor($this->usuarioLogado()->id_vendedor);
        }
        return $this->_verificarData($data);
    }

    /**
     * Retorna as embalagem dos produtos
     * @return null|string
     */
    public function _produtoEmbalagem()
    {
        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_PRODUTO_EMBALAGEM)) {
            return $this->_verificarData($this->_getAllPorTabela(TABELA_PRODUTO_EMBALAGEM));
        } else {
            return $this->_verificarData(NULL);
        }
    }

    /**
     * Retorna tabela de preço por item
     * @return null|string
     */
    private function _tabelaprecoitem()
    {
        if ($this->restricao_protabela_preco) {
            $data = $this->getItensTabPreco($this->usuarioLogado()->id_vendedor);
        } else {
            $data = $this->getItensTabPreco();
        }

        return $this->_verificarData($data);
    }

    /**
     * Retorna a tabela de preço
     * @return null|string
     */
    private function _tabelapreco()
    {
        if ($this->restricao_protabela_preco) {
            $data = $this->getTabPreco($this->usuarioLogado()->id_vendedor);
        } else {
            $data = $this->getTabPreco();
        }

        return $this->_verificarData($data);
    }

    /**
     * Retorna o desconto por quantidade
     * @return null|string
     */
    private function _produtoDesctoQtd()
    {
        if ($this->restricao_protabela_preco) {
            $data = $this->getProdutoDesctoQtd($this->usuarioLogado()->id_vendedor);
        } else {
            $data = $this->getProdutoDesctoQtd();
        }

        return $this->_verificarData($data);
    }

    /**
     * Retorna os produtos
     * @return null|string
     */
    private function _produto()
    {
        if ($this->periodoSincronizacao()->restricao_produto == 1) {
            $data = $this->produtoPorVendedor($this->usuarioLogado()->id_vendedor, TRUE);
        } else {
            $data = $this->produtoPorVendedor(NULL, FALSE);
        }
        return $this->_verificarData($data);
    }

    /**
     * Retorna os grupos
     * @return null|string
     */
    private function _grupo()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_GRUPO));
    }

    /**
     * Retorna os sub grupos
     * @return null|string
     */
    private function _subGrupo()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_SUBGRUPO));
    }

    /**
     * Retorna os status do produto
     * @return null|string
     */
    private function _statusProduto()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_STATUS_PRODUTO));
    }

    /**
     * Retorna o ipi
     * @return null|string
     */
    private function _ipi()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_PRODUTO_IPI));
    }

    /**
     * Retorna o st
     * @return null|string
     */
    private function _st()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_PRODUTO_ST));
    }

    /**
     * Retorna os mix de produtos
     * @return null|string
     */
    public function _mixproduto()
    {
        return $this->_verificarData($this->_retornoDadosPorVendedorOuSupervisor(TABELA_MIX_PRODUTO));
    }

    /**
     * Retorna os fornecedores
     * @return null|string
     */
    public function _fornecedor()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_FORNECEDOR));
    }

    /**
     * @param null $dtModificado
     * @return null|string
     */
    public function _produtoEstoque($dtModificado = NULL)
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_PRODUTO_ESTOQUE, NULL, $dtModificado != NULL ? "dt_modificado > '{$dtModificado}'" : NULL));
    }

    private function periodoSincronizacao()
    {
        $data = PeriodoSincronizacao::select(
            "restricao_protabela_preco",
            "restricao_produto"
        )
            ->where("fk_empresa", $this->usuarioLogado()->fk_empresa)
            ->first();

        return $data;
    }

    private function produtoPorVendedor($id_vendedor = NULL, $por_vendedor = FALSE)
    {
        $data = Produto::select("produto.*");

        //Se a configuração vendedor_produto == 1
        if ($por_vendedor != FALSE) {

            $existe_vendedor = VendedorProduto::select("count(id_vendedor) as total")
                ->where("id_vendedor", $id_vendedor)->count() > 0 ? TRUE : FALSE;

            //verifica se existe algum cadastro do vendedor na tabela
            if ($existe_vendedor) {
                $data->addSelect(
                    DB::raw("IF(grupo.status=0,0, IF(subgrupo.status=0,0, IF(status_produto.id_retaguarda = \"S\" AND status_produto.id_retaguarda = \"F\", 0,IF(vendedor_produto.id_vendedor IS NULL,0,1)))) AS podeVender")
                )
                    ->leftJoin("vendedor_produto", function ($query) use ($id_vendedor) {
                        $query->on("vendedor_produto.id_produto", "=", "produto.id");
                        $query->on("vendedor_produto.id_vendedor", "=", $id_vendedor);
                    });
            } else { //se não existir ele retorna sempre tudo
                $data->addSelect(
                    DB::raw("IF(grupo.status=0,0, IF(subgrupo.status=0,0, IF(status_produto.id_retaguarda = \"S\" AND status_produto.id_retaguarda = \"F\", 0,1))) AS podeVender")
                );
            }
        } else {
            $data->addSelect(
                DB::raw("IF(grupo.status=0,0, IF(subgrupo.status=0,0, IF(status_produto.id_retaguarda <> \"S\" AND status_produto.id_retaguarda <> \"F\", 1,0))) AS podeVender")
            );
        }

        $data->join("grupo", function ($query) {
            $query->on("grupo.id_retaguarda", "=", "produto.id_grupo");
            $query->on("grupo.id_filial", "=", "produto.id_filial");
        });

        $data->join("subgrupo", function ($query) {
            $query->on("subgrupo.id_retaguarda", "=", "produto.id_subgrupo");
            $query->on("grupo.id_retaguarda", "=", "subgrupo.id_grupo");
            $query->on("subgrupo.id_filial", "=", "produto.id_filial");
        });

        $data->join("status_produto", "status_produto.id", "=", "produto.status");

        return $data->get();
    }

    private function getTabPreco($idVendedor = NULL)
    {
        $data = ProtabelaPreco::where(
            function ($query) {
                $query->where("protabela_preco.tab_fim", ">=", date('Y-m-d'))
                    ->orWhereNull("protabela_preco.tab_fim");
            }
        )
            ->where(
                function ($query) {
                    $query->where("protabela_preco.tab_ini", "<=", date('Y-m-d'))
                        ->orWhereNull("protabela_preco.tab_ini");
                }
            );

        if (!is_null($idVendedor)) {
            $data->join("vendedor_protabelapreco", "vendedor_protabelapreco.id_protabela_preco", "=", "protabela_preco.id");
            $data->where("vendedor_protabelapreco.id_vendedor", $idVendedor);
        }

        return $data->get();
    }

    private function getItensTabPreco($idVendedor = NULL)
    {
        $data = ProtabelaIten::select("protabela_itens.*")
            ->join("protabela_preco", "protabela_preco.id", "=", "protabela_itens.id_protabela_preco")
            ->where(
                function ($query) {
                    $query->where("protabela_preco.tab_fim", ">=", date('Y-m-d'))
                        ->orWhereNull("protabela_preco.tab_fim");
                }
            )
            ->where(
                function ($query) {
                    $query->where("protabela_preco.tab_ini", "<=", date('Y-m-d'))
                        ->orWhereNull("protabela_preco.tab_ini");
                }
            );

        if (!is_null($idVendedor)) {
            $data->join("vendedor_protabelapreco", "vendedor_protabelapreco.id_protabela_preco", "=", "protabela_preco.id");
            $data->where("vendedor_protabelapreco.id_vendedor", $idVendedor);
        }

        return $data->distinct()->get();
    }

    private function getProdutoDesctoQtd($idVendedor = NULL)
    {
        $data = ProdutoDesctoQtd::select("produto_descto_qtd.*")
            ->join("protabela_preco", "protabela_preco.id", "=", "produto_descto_qtd.id_protabela_preco")
            ->join("vendedor_protabelapreco", "vendedor_protabelapreco.id_protabela_preco", "=", "protabela_preco.id")
            ->where(
                function ($query) {
                    $query->where("protabela_preco.tab_fim", ">=", date('Y-m-d'))
                        ->orWhereNull("protabela_preco.tab_fim");
                }
            )
            ->where(
                function ($query) {
                    $query->where("protabela_preco.tab_ini", "<=", date('Y-m-d'))
                        ->orWhereNull("protabela_preco.tab_ini");
                }
            );

        if (!is_null($idVendedor)) {
            $data->where("vendedor_protabelapreco.id_vendedor", $idVendedor);
        }

        return $data->get();
    }

    private function getCampanhaVendedor($idVendedor)
    {
        $data = Campanha::select("campanha.*")
            ->leftJoin("campanha_participante", "campanha_participante.id_campanha", "=", "campanha.id")
            ->where("campanha.status", STATUS_ATIVO)
            ->where("campanha_participante.id_retaguarda", $idVendedor)
            ->orWhere("campanha_participante.id_retaguarda", NULL)
            ->get();

        return $data;
    }

    private function getCampanhaBeneficioVendedor($idVendedor)
    {
        $data = CampanhaBeneficio::select("campanha_beneficio.*")
            ->leftJoin("campanha_participante", "campanha_participante.id_campanha", "=", "campanha_beneficio.id_campanha")
            ->where("campanha_beneficio.status", STATUS_ATIVO)
            ->where("campanha_participante.id_retaguarda", $idVendedor)
            ->orWhere("campanha_participante.id_retaguarda", NULL)
            ->get();

        return $data;
    }

    private function getCampanhaModalidadeVendedor($idVendedor)
    {
        $data = CampanhaModalidade::select("campanha_modalidade.*")
            ->leftJoin("campanha_participante", "campanha_participante.id_campanha", "=", "campanha_modalidade.id_campanha")
            ->where("campanha_participante.id_retaguarda", $idVendedor)
            ->orWhere("campanha_participante.id_retaguarda", NULL)
            ->get();

        return $data;
    }

    private function getCampanhaRequisitoVendedor($idVendedor)
    {
        $data = CampanhaRequisito::select("campanha_requisito.*")
            ->leftJoin("campanha_participante", "campanha_participante.id_campanha", "=", "campanha_requisito.id_campanha")
            ->where("campanha_requisito.status", STATUS_ATIVO)
            ->where("campanha_participante.id_retaguarda", $idVendedor)
            ->orWhere("campanha_participante.id_retaguarda", NULL)
            ->get();

        return $data;
    }
}
