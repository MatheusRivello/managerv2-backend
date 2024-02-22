<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Cliente;
use App\Models\Tenant\ClienteReferencia;
use App\Models\Tenant\Contato;
use App\Models\Tenant\Endereco;
use App\Models\Tenant\Referencia;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CLIENTE;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = self::_dadosClass()["metodo"];
        $this->entity = ClienteServicoController::class;
        $this->firstOrNew = ["id_filial", "id_retaguarda"];
        $this->acaoTabela = self::_dadosClass()["acao"];
        $this->model = Cliente::class;
        $this->customCasts = [
            "headCliente" => ["id"],
            "endereco" => ["codigo_ibge", "tit_cod"],
            "contato" => ["id_cliente", "con_cod"],
            "referencia" => ["id_cliente", "id_referencia", "id"]
        ];
    }

    public static function atualizarDados($array, $erroPacote)
    {
        try {
            $erros = NULL;
            $vetorInsercao = NULL;
            $totalInseridos = 0;
            $totalAtualizados = 0;
            $totalRegistros = count($array);
            $erroValidacaoIds = NULL;
            $service = new BaseService();

            if (!is_null($erroPacote))
                $erros = $erroPacote;

            $vetor = [
                "filial" => $service->getAllId("filial"),
                "atividade" => $service->getAllIds("atividade"),
                "statusCliente" => $service->getAllId("status_cliente", TRUE),
                "cliente" => $service->getAllIds("cliente")
            ];

            if (is_null($vetor["statusCliente"]))
                $erroValidacaoIds = "(Status Cliente)";
            if (is_null($vetor["filial"]))
                $erroValidacaoIds .= "(Filial)";
            if (is_null($vetor["atividade"]))
                $erroValidacaoIds .= "(Atividade)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                foreach ($array as $key => $cliente) {
                    $validacao = $service->_validacao(self::_valoresNull($cliente), self::_nomeCamposDb());
                 
                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idAtividade = isset($vetor["atividade"][$item["id_filial"]][$item["id_atividade"]]) ? $vetor["atividade"][$item["id_filial"]][$item["id_atividade"]] : NULL;
                        $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_retaguarda"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_retaguarda"]] : NULL;
                        $idStatusCliente = isset($vetor["statusCliente"][$item["id_status"]]) ? $vetor["statusCliente"][$item["id_status"]] : NULL;

                        if (is_null($idFilial))
                            $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idStatusCliente))
                            $erroValidacaoIds .= "(Status Cliente={$item["id_status"]})";
                        if (is_null($idAtividade))
                            $erroValidacaoIds .= "(Atividade={$item["id_atividade"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $item["id_status"] = intval($idStatusCliente);
                            $item["id_atividade"] = intval($idAtividade);

                            if (!is_null($idCliente)) {
                                $item["id"] = intval($idCliente);
                                $vetorAtualizacao[] = $item;
                                $totalAtualizados++;
                            } else {
                                $vetorInsercao[] = $item;
                                $totalInseridos++;
                            }
                        }
                    } else {
                        $erros[] = $validacao;
                    }
                }

                unset($vetor);

                if (isset($vetorInsercao)) {
                    $retornoInsert = self::insertBatch($vetorInsercao);
                    if ($retornoInsert != NULL)
                        $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
                }

                if (isset($vetorAtualizacao)) {
                    $retornoAtualizacao = self::atualizarBatch($vetorAtualizacao);
                    if ($retornoAtualizacao != NULL)
                        $erros[] = ["erro" => PACOTE_NAO_ATUALIZADO];
                }
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalAtualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetores($dados, Cliente::class);
    }

    private function atualizarBatch($dados)
    {
        return parent::updateVetores($dados, Cliente::class, "id", self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial",
            "id_retaguarda",
            "id_tabela_preco",
            "id_prazo_pgto",
            "id_forma_pgto",
            "razao_social",
            "nome_fantasia",
            "cnpj",
            "email",
            "id_status",
            "id_atividade",
            "telefone",
            "tipo",
            "tipo_contribuinte",
            "site",
            "email_nfe",
            "limite_credito",
            "saldo",
            "saldo_credor",
            "sinc_erp",
            "observacao",
            "intervalo_visita",
            "dt_ultima_visita",
            "bloqueia_forma_pgto",
            "avencer",
            "media_dias_atraso",
            "bloqueia_prazo_pgto",
            "bloqueia_tabela",
            "inscricao_municipal",
            "inscricao_rg",
            "ven_cod",
            "atraso_tot",
            "dt_ultima_compra"
        );
    }

    protected function _valoresNull($dados)
    {
        if (!isset($dados["dt_ultima_visita"]) || $dados["dt_ultima_visita"] == "")
            $dados["dt_ultima_visita"] = NULL;
        if (!isset($dados["atraso_tot"]) || $dados["atraso_tot"] == NULL || $dados["atraso_tot"] == "")
            $dados["atraso_tot"] = "0.0";
        if (!isset($dados["avencer"]) || $dados["avencer"] == NULL || $dados["avencer"] == "")
            $dados["avencer"] = "0.0";
        if (!isset($dados["saldo"]) || $dados["saldo"] == NULL || $dados["saldo"] == "")
            $dados["saldo"] = "0.0";
        if (!isset($dados["saldo_credor"]) || $dados["saldo_credor"] == NULL || $dados["saldo_credor"] == "")
            $dados["saldo_credor"] = "0.0";
        if (!isset($dados["dt_ultima_compra"]) || $dados["dt_ultima_compra"] == "")
            $dados["dt_ultima_compra"] = NULL;

        return $dados;
    }

    /**
     * Retorna a lista dos novos clientes
     */
    public function novos()
    {
        $data = json_decode(json_encode($this->getNovosClientes(random_int(1, 3))), true);
    
        $resposta = is_null($data) ? [
            "status" => "erro",
            "code" => HTTP_NOT_FOUND,
            "mensagem" => "Nenhum registro localizado"
        ] : [
                "status" => "sucesso",
                "code" => HTTP_ACCEPTED,
                "data" => $data
            ];

        return response()->json($resposta, 200);
    }

    private function getNovosClientes($limit)
    {
        $cabecalho = $this->model::withCasts($this->util->convertValueJSON($this->model, $this->customCasts["headCliente"], true))->select(
            "cliente.id",
            "cliente.id_filial",
            "atividade.id_retaguarda",
            "cliente.id_tabela_preco",
            "cliente.id_prazo_pgto",
            "cliente.id_forma_pgto",
            "cliente.id_status",
            "cliente.razao_social",
            "cliente.nome_fantasia",
            "cliente.cnpj",
            "cliente.senha",
            "cliente.email",
            "cliente.codigo_tempo",
            "cliente.codigo_senha",
            DB::raw("atividade.id_retaguarda AS id_atividade"),
            "cliente.telefone",
            "cliente.tipo",
            "cliente.tipo_contribuinte",
            "cliente.site",
            "cliente.email_nfe",
            "cliente.limite_credito",
            "cliente.saldo",
            "cliente.sinc_erp",
            "cliente.observacao",
            "cliente.intervalo_visita",
            "cliente.dt_ultima_visita",
            "cliente.dt_cadastro",
            "cliente.dt_modificado",
            "cliente.bloqueia_forma_pgto",
            "cliente.bloqueia_prazo_pgto",
            "cliente.bloqueia_tabela",
            "cliente.id_mobile",
            "cliente.inscricao_municipal",
            "cliente.inscricao_rg",
            "cliente.ven_cod",
            "cliente.integra_web",
            "cliente.atraso_tot",
            "cliente.avencer",
            "cliente.media_dias_atraso",
            DB::raw("status_cliente.id_retaguarda as status")
        )
            ->leftJoin("status_cliente", "status_cliente.id", "=", "cliente.id_status")
            ->join("atividade", "atividade.id", "=", "cliente.id_atividade")
            ->where("cliente.sinc_erp", PENDENTE_SYNC_ERP)
            ->orderBy("cliente.id", "desc") // SO RETORNA OS REGISTROS NOVOS QUE PRECISAM ENVIAR PARA O BANCO DO SISTEMA LOCAL
            ->limit($limit)
            ->get();

        $dados = [];
        if ($cabecalho != NULL) {
            foreach ($cabecalho as $item) {
                $item["endereco"] = $this->getEndereco($item["id"]);
                $item["contato"] = $this->getContato($item["id"]);
                $item["referencia"] = $this->getReferencia($item["id"]);
                $item["anexo"] = [];

                array_push($dados, $item);
            }
        }
        return $dados;
    }

    private function getEndereco($idCliente)
    {
        $modelEndereco = Endereco::class;

        $data = $modelEndereco::withCasts(
            $this->util->convertValueJSON(
                $modelEndereco,
                $this->customCasts["endereco"],
                true
            )
        )->select(
                "endereco.id_cliente",
                "endereco.tit_cod",
                "endereco.id_cidade as codigo_ibge",
                "endereco.cep",
                "endereco.logradouro",
                "endereco.numero",
                "endereco.complemento",
                "endereco.bairro",
                "endereco.uf",
                "cidade.descricao AS cidade",
                "endereco.latitude",
                "endereco.longitude",
                "endereco.referencia"
            )
            ->join("cidade", "cidade.id", "=", "endereco.id_cidade")
            ->where("endereco.id_cliente", $idCliente)
            ->orderBy("endereco.tit_cod", "ASC")
            ->get();

        return $data;
    }

    private function getContato($idCliente)
    {
        $modelContato = Contato::class;

        $data = $modelContato::withCasts(
            $this->util->convertValueJSON(
                $modelContato,
                $this->customCasts["contato"]
            )
        )
            ->where("contato.id_cliente", $idCliente)
            ->orderBy("contato.con_cod", "ASC")
            ->get();

        return $data;
    }

    private function getReferencia($idCliente)
    {
        $modelReferencia = Referencia::class;

        $data = $modelReferencia::withCasts(
            $this->util->convertValueJSON(
                $modelReferencia,
                $this->customCasts["referencia"]
            )
        )
            ->join("cliente_referencia", "cliente_referencia.id_referencia", "=", "referencia.id")
            ->where("cliente_referencia.id_cliente", $idCliente)
            ->get();

        return $data;
    }

    /**
     * Atualiza O ID do retaguarda para os novos
     */
    public function atualizaClientes(Request $request)
    {
        $log = NULL;
        $array = isset($request->json) ? json_decode($request->json, true) : $request->all();
        $select = ["id", "id_filial", "cnpj", "id_retaguarda"];
        $resposta = $this->service->_validarArray($array);

        if ($resposta) {
            foreach ($array as $chave => $valor) {
                $id_retaguarda = $valor["id_cliente"]; // ID QUE VEM DO RETAGUARDA
                $id_interno = $valor["id_interno"]; // ID DO BANCO DA NUVEM


                //Retornar dados do cliente novo
                $clienteNovo = $this->getInfoCliente($select, ["id" => $id_interno]);

                //Retorna dados do cliente caso já estiver cadastrado
                $clienteNuvem = $this->getInfoCliente($select, ["id_retaguarda" => $id_retaguarda]);

                if (
                    !is_null($clienteNuvem) &&
                    $clienteNuvem["id_filial"] == $clienteNovo["id_filial"] &&
                    ($clienteNuvem["id_retaguarda"] == $id_retaguarda || $clienteNuvem["cnpj"] == $clienteNovo["cnpj"])
                ) {
                    $resultado = $this->atualizarIdRetaguarda($id_interno, $id_retaguarda);
                    $log[$id_interno] = "OK";
                } else {
                    $resultado = $this->atualizarIdRetaguarda($id_interno, $id_retaguarda);
                    $log[$id_interno] = $resultado ? 'OK' : 'FALHA';
                }

            }

            $resposta = (count($log) > 0) ? [
                "code" => HTTP_ACCEPTED,
                "status" => "successo",
                "mensagem" => $log
            ] : [
                    "code" => HTTP_NOT_FOUND,
                    "status" => "error",
                ];
        }

        return response()->json($resposta, HTTP_CREATED);
    }

    private function getInfoCliente($select, $where)
    {
        $cliente = $this->model::select($select)->where($where)->first();

        return isset($cliente) ? $cliente : NULL;
    }

    private function deletarClienteSync($idInterno)
    {
        $this->model::find($idInterno)->delete();
    }

    public function atualizarIdRetaguarda($id_interno, $id_retaguarda)
    {
        $retorno = false;

        $cliente = $this->model::find($id_interno);

        if (isset($cliente)) {
            $cliente->id_retaguarda = $id_retaguarda;
            $cliente->sinc_erp = SYNC_NAO_BAIXAR_SIG;

            if ($cliente->save()) {
                Endereco::where("id_cliente", $id_interno)->update(["sinc_erp" => SYNC_NAO_BAIXAR_SIG]);
                Contato::where("id_cliente", $id_interno)->update(["sinc_erp" => SYNC_NAO_BAIXAR_SIG]);

                $retorno = true;
            }
        }
        return $retorno;
    }

    private function deletaReferenciaPorClientes($arrayIdsClientes)
    {
        $idsReferencias = ClienteReferencia::select("id_referencia")
            ->whereIn("id_cliente", $arrayIdsClientes)->get();

        if (!is_null($idsReferencias)) {
            $arrayIds = [];

            foreach ($idsReferencias as $id) {
                array_push($arrayIds, $id["id_referencia"]);
            }

            Referencia::whereIn("id", $arrayIds)->delete();
        }

        return;
    }

    private function _dadosClass()
    {
        return array(
            "metodo" => 'Cliente' . CLASS_SERVICE,
            "acao" => 0
        );
    }
}
