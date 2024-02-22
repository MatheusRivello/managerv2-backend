<?php

namespace App\Http\Controllers\externa;

use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


abstract class AbstractExternaController
{
    protected $Entity;
    protected $service;
    protected $filters;
    protected $relationCountMethodName;
    protected $rulePath;
    protected $fields;
    protected $firstOrNew;
    protected $model;
    protected $modelComBarra;
    protected $tabela;
    protected $where;

    public function __construct(BaseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $limite = (isset($request->pageSize) && $request->pageSize > 1) ? $request->pageSize : 20;
            return $this->service->verificarErro($this->Entity::paginate($limite));
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 400);
        }
    }

    public function show(Request $request)
    {
        try {
            $result = $this->Entity::where(function ($query) use ($request) {
                foreach ($this->filters as $key => $filter) {
                    if (!is_null($request->{$filter})) $query->whereIn($filter, $request->{$filter});
                }
            })
                ->paginate(20);

            return $this->service->verificarErro($result);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            
            $this->service->verificarCamposRequest($request, $this->rulePath, isset($request->idIgnorar) ? $request->idIgnorar : null);
            
           $model = $this->Entity::firstOrNew($this->mountFirstOrNewParam($request)($this->firstOrNew));
         
            foreach ($this->fields as $key => $field) {
                $model->{$field} = $request->{$this->parseCamelCase($field)};
            }
            $model->save();
            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage(), "code" => $e->getCode()]);
        }
    }

    public function storePersonalizado(Request $request)
    {

        try {
            
            $this->service->verificarCamposRequest($request, $this->rulePath);

            $model = $this->Entity::firstOrNew($this->mountFirstOrNewParam($request)($this->firstOrNew));

            foreach ($this->fields as $key => $field) {
                $conversao = $request->{$this->parseCamelCase($field)};
                $where[$field] = $conversao;
                $model->{$field} = $conversao;
            }
            $this->service->countChaveComposta(MODEL_TENANT, $this->modelComBarra, $where);
            $model->save();
            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage(), "code" => $e->getCode()]);
        }
    }

    public function storePersonalizado2($object)
    {

        try {
            
            $this->service->verificarCamposRequest($object, $this->rulePath);
            $model = $this->Entity::firstOrNew($this->mountFirstOrNewParam($object)($this->firstOrNew));
           
            foreach ($this->fields as $key => $field) {
                $conversao = $object->{$this->parseCamelCase($field)};
                $where[$field] = $conversao;
                $model->{$field} = $conversao;
            }
            
            $this->service->countChaveComposta(MODEL_TENANT, $this->modelComBarra, $where);
            $model->save();
            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage(), "code" => $e->getCode()]);
        }
    }

    public function destroy($id)
    {
        try {
            (float) $id;
        
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }
            $registro = $this->Entity::find($id);
            
            if (!isset($registro)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            } else if ($registro->{$this->relationCountMethodName}() > 0) {
                throw new Exception(EXISTE_RELACIONAMENTOS, 406);
            }
            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()],$e->getCode() == 0 ? 500 : $e->getCode());
        }
    }

    public function destroyWhere($where)
    {
           
        try {
            $resultado = DB::connection($this->service->connection('driver'))->table($this->tabela)->where($where)->delete();

            if ($resultado == true) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            } else {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 403);
            }
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 400);
        }
    }

    private function mountFirstOrNewParam($request)
    {
        return function ($firstOrNew) use ($request) {
            $arr = [];
            foreach ($firstOrNew as $key => $value) {
                $arr[$value] = $request->{$this->parseCamelCase($value)};
            }
            return $arr;
        };
    }

    private function parseCamelCase($field)
    {
        if (str_contains($field, "_") && $field !== "APRESENTA_VENDA") {
            return str_replace("_", "", lcfirst(ucwords($field, "_")));
        }
        return $field;
    }
}
