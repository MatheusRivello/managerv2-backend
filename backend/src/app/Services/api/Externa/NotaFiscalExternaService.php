<?php

namespace App\Services\api\Externa;

use Exception;
use App\Models\Tenant\NotaFiscal;
use App\Services\BaseService;


class NotaFiscalExternaService extends BaseService
{

    public function verificarNotaFiscalFilial($dados)
    {
        $notaFiscal = NotaFiscal::where(["id_filial" => $dados->idFilial, "nfs_doc" => $dados->nfsDoc, "nfs_serie" => $dados->nfsSerie])->first();

        if (!isset($notaFiscal) || $notaFiscal->id_cliente == $dados->idCliente) {
          return $dados;   
        }else{
          throw new Exception(EXISTE_REGISTRO, 400);
        }
   
    }
}
