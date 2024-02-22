<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Rastro;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class RastroServicoController extends BaseServicoController
{

    public function __construct(BaseService $service)
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_RASTRO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Rastro' . CLASS_SERVICE;
        $this->entity = RastroServicoController::class;
        $this->firstOrNew = ["id"];
        $this->acaoTabela = 0;
        $this->model = Rastro::class;
        $this->idEmpresa = $service->usuarioLogado()->fk_empresa;
    }


    /**
     * Retorna a lista dos novas configurações
     */
    public function getNovosRastros()
    {
        //Inativo
        // $data = Rastro::where("sinc_erp", 1)->limit(1000)->get(); // Só irá trazer os rastros que forem novos

        $data = NULL; //Não irá retornar os rastros

        $resposta = is_null($data) ? [
            'status' => 'erro',
            'code' => HTTP_NOT_FOUND,
            'mensagem' => 'Não há registros novos para serem baixados '
        ] : [
            'status' => 'sucesso',
            'code' => HTTP_ACCEPTED,
            'data' => $data
        ];

        return response()->json($resposta, $resposta["code"]);
    }

    /**
     * Atualiza O Sinc_erp atraves do id da nuvem
     */
    public function atualizaRastros(Request $request)
    {
        $log = [];
        $resposta = NULL;
        $resposta = $this->service->_validarArray($request->json);
        try {
            if ($resposta) {

                foreach ($request->json as $chave => $valor) {

                    $id_interno = $chave; // ID da nuvem
                    $valor_sinc_erp = $valor; // Valor do Sinc_erp deverá vir sempre (0) do sistema local

                    $validacao = $this->validaID($id_interno);

                    if (!$validacao) {
                        $log[$id_interno] = "INVALIDO";
                    } else {
                        $arrayDados = [
                           "id" => $id_interno,
                           "sinc_erp" => $valor_sinc_erp
                        ];

                        $resultado = self::atualizarInserir($arrayDados);

                        $log[$id_interno] = $resultado ? 'OK' : 'FALHA'; // Infoma se ouve Sucesso ou Erro
                    }

                }

                $resposta = (count($log) > 0) ? [
                    'code' => HTTP_ACCEPTED,
                    'status' => 'successo',
                    'mensagem' => $log
                ] : [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                ];
            }

            return response()->json($resposta, $resposta["code"]);
        } catch (Exception $ex) {
            return  $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function _nomeCamposDb()
    {
        return array(
            'id',
            'sinc_erp'
        );
    }

    public function validaID($idInterno)
    {

        $rastro = $this->model::find($idInterno)->count();

        return $rastro > 0 ? TRUE : FALSE;
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, Rastro::class, ["id"], self::_nomeCamposDb());
    }
}
