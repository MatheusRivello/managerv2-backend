<?php

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\NotaFiscal;

use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Models\Tenant\NotaFiscalItem;
use Exception;

class NotaFiscalExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = NotaFiscal::class;
        $this->filters = ['id', 'id_filial', 'ped_num', 'id_cliente', 'id_vendedor', 'nfs_doc', 'nfs_serie', 'nfs_status', 'nfs_emissao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_NOTA_FISCAL_EXTERNA;
        $this->firstOrNew = ['id_filial', 'ped_num', 'id_cliente', 'id_vendedor'];
        $this->fields = [
            "id_filial",
            "ped_num",
            "id_cliente",
            "id_vendedor",
            "nfs_doc",
            "nfs_serie",
            "nfs_status",
            "nfs_emissao",
            "nfs_valbrut",
            "nfs_tipo",
            "ped_entrega",
            "prazo_pag",
            "forma_pag",
            "ped_emissao",
            "ped_total",
            "nfs_custo",
            "observacao"
        ];
    }
    public function storeNotaFiscalItem(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_NOTA_FISCAL_EXTERNA);
            $this->service->countChaveComposta(MODEL_TENANT, '\NotaFiscal', ['id_filial' => $request->idFilial, 'nfs_doc' => $request->nfsDoc, 'nfs_serie' => $request->nfsSerie]);
            $notafiscal = new NotaFiscal;
            $notafiscal->id_filial = $request->idFilial;
            $notafiscal->ped_num = $request->pedNum;
            $notafiscal->id_cliente = $request->idCliente;
            $notafiscal->id_vendedor = $request->idVendedor;
            $notafiscal->nfs_doc = $request->nfsDoc;
            $notafiscal->nfs_serie = $request->nfsSerie;
            $notafiscal->nfs_doc = $request->nfsDoc;
            $notafiscal->nfs_serie = $request->nfsSerie;
            $notafiscal->nfs_status = $request->nfsStatus;
            $notafiscal->nfs_emissao = $request->nfsEmissao;
            $notafiscal->nfs_valbrut = $request->nfsValbrut;
            $notafiscal->nfs_tipo = $request->nfsTipo;
            $notafiscal->ped_entrega = $request->pedEntrega;
            $notafiscal->prazo_pag = $request->prazoPag;
            $notafiscal->forma_pag = $request->formaPag;
            $notafiscal->ped_emissao = $request->pedEmissao;
            $notafiscal->ped_total = $request->pedTotal;
            $notafiscal->nfs_custo = $request->nfsCusto;
            $notafiscal->observacao = $request->observacao;
            
            foreach ($request->notaFiscalItem as $key => $requestItem) {
                $item = new notaFiscalItem();
                $item->id_filial = $requestItem["idFilial"];
                $item->ped_num = $requestItem["pedNum"];
                $item->id_produto = $requestItem["idProduto"];
                $item->nfs_doc = $requestItem["nfsDoc"];
                $item->nfs_serie = $requestItem["nfsSerie"];
                $item->nfs_status = $requestItem["nfsStatus"];
                $item->nfs_qtd = $requestItem["nfsQtd"];
                $item->nfs_unitario = $requestItem["nfsUnitario"];
                $item->nfs_desconto = $requestItem["nfsDesconto"];
                $item->nfs_descto = $requestItem["nfsDescto"];
                $item->nfs_total = $requestItem["nfsTotal"];
                $item->ped_qtd = $requestItem["pedQtd"];
                $item->ped_total = $requestItem["pedTotal"];
                $item->nfs_custo = $requestItem["nfsCusto"];
                $item->save();
            }
            $notafiscal->save();
            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(["error" => true, "message" => $ex->getMessage()], 400);
        }
    }
    public function getNotaFiscalItem(Request $request)
    {
        $where = ['ped_num' => $request->pedNum];
        $where = NotaFiscal::where($where)
            ->with('nota_fiscal_item')
            ->get();
        return $where;
    }
}
