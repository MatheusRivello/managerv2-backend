<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\FormaPrazoPgto;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class FormaPrazoDePagamentoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = FormaPrazoPgto::class;
        $this->filters = ['id_forma_pgto', 'id_prazo_pgto'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_FORMA_PRAZO_DE_PAGAMENTO_EXTERNA;
        $this->firstOrNew = ['id_forma_pgto', 'id_prazo_pgto'];
        $this->fields = [
            'id_forma_pgto',
            'id_prazo_pgto'
        ];
        $this->tabela = 'forma_prazo_pgto';
        $this->modelComBarra = "\FormaPrazoPgto";
    }

    public function destroyPersonalizado(Request $request)
    {
        try {

            if (isset($request->idFormaPgto)) {
                $this->where = ["id_forma_pgto" => $request->idFormaPgto, 'id_prazo_pgto' => $request->idPrazoPgto];
            }
            if (!isset($this->where)) {
                throw new Exception(ERRO_VARIAVEL_INDEFINIDA, 400);
            }
            return $this->destroyWhere($this->where);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 406);
        }
    }
}
