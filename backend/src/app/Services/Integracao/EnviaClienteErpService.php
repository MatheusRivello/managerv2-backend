<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use Illuminate\Support\Facades\DB;
use App\Services\Dto\ClienteErpDto;

class EnviaClienteErpService extends SendDataToErpService
{
    protected $idCliente;
    protected $data;
    protected $cliente;
    protected $logFolder = "cliente";

    protected function getCliente()
    {
        try {
            DB::setDefaultConnection("empresa" . $this->tenant);

            $cliente = Cliente::with("contatos:id_cliente,nome,telefone")
                ->with(["enderecos" => function ($query) {
                    $query->select(
                        "id_cliente",
                        "bairro",
                        "cep",
                        DB::raw("id_cidade as cidade_codigo_ibge"),
                        "complemento",
                        "logradouro",
                        "numero",
                        "referencia",
                        "tit_cod as tipo_endereco"
                    );
                }])
                ->find($this->idCliente);

            $this->cliente = $cliente;

            $clienteDto = new ClienteErpDto($cliente);

            $this->data = $clienteDto;
        }
        catch (\Exception $e)
        {
            $this->writeLog(
                "Ocorreu um erro ao pegar o cliente. " . $e->getMessage(),
                "/cliente/erro"
            );
        }
    }

    protected function saveCliCodAsIdRetaguarda()
    {
        if ($this->httpCode == 200)
        {
            try {
                $data = $this->response->data;

                $id_retaguarda = $data->codigoClienteInserido;

                $this->writeLog("Cliente $id_retaguarda inserido no ERP.", "/cliente"."/");

                $this->cliente->id_retaguarda = $id_retaguarda;

                $this->cliente->sinc_erp = 0;

                $this->cliente->save();
                DB::commit();
            }
            catch (\Exception $e)
            {
                $this->writeLog(
                    "Ocorreu um erro ao salvar o id_retaguarda do cliente. ". $e->getMessage(),
                    "/cliente/erro"
                );
            }
        }
    }
}