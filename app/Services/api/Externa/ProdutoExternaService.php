<?php

namespace App\Services\api\Externa;

use App\Models\Tenant\Grupo;
use App\Models\Tenant\Subgrupo;
use App\Services\BaseService;
use Exception;

class ProdutoExternaService extends BaseService
{
    public function getIdNuvem($idFilial, $idGrupo, $idSubgrupo = NULL, $mostrarErro = TRUE)
    {
        $resultado = [];
        $resultado['grupo'] = Grupo::select('id')->where([['id_retaguarda', $idGrupo], ['id_filial', $idFilial]])->first()->id;

        if (!isset($resultado['grupo'])) {
            throw new Exception("Não existe nenhum grupo com o idRetaguarda {$idGrupo}", 404);
        }

        if (isset($idSubgrupo)) {
            $resultado['subGrupo'] = Subgrupo::select('id')->where([['id_grupo', $idGrupo], ['id_retaguarda', $idSubgrupo], ['id_filial', $idFilial]])->first()->id;
        }

        if (!isset($resultado['subGrupo']) && $mostrarErro == TRUE) {
            throw new Exception("Não existe nenhum subgrupo com o idRetaguarda informado {$idSubgrupo} nesse grupo {$idGrupo}", 404);
        }

        return $resultado;
    }
}
