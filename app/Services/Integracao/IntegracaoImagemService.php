<?php

namespace App\Services\Integracao;

use App\Http\Controllers\servico\v1\ERP\ImagemServicoController;
use App\Services\BaseService;
use App\Services\Util\ImagemService;

abstract class IntegracaoImagemService extends IntegracaoService
{
    private $imageService;

    function __construct($params)
    {
        parent::__construct($params);
        $baseService = new BaseService();
        $baseService->setManualTenantId($params['tenant']);
        $this->imageService = new ImagemService($baseService);
    }

    protected function insertInDB()
    {
        $data = $this->data;
        $info = [
            'saved' => 0,
            'notSaved' => 0,
        ];

        foreach ($data as $image)
        {
            try {
                $clazz = new \stdclass();
                $clazz->id_filial = $image->emp_cod;
                $clazz->id_retaguarda = $image->pro_reduzi;
                $clazz->arquivo = $image->imagem;
                $clazz->sequencia = $image->sequencia;
                $res = $this->imageService->upload($clazz);

                if ($res['status'] == 'sucesso') $info['saved']++;

                if ($res['status'] == 'erro') $info['notSaved']++;
            }
            catch (\Throwable $th)
            {
                $this->addLog("Erro em " . $this->modelName . " com status " . $th->getCode() . ". " . $th->getMessage(), $th);
            }
        }
        $this->saveLog("erros-detalhados/" . date("H\h"));

        $this->writeLog("[Pacote " . (intdiv($this->offset, $this->limit)) . "] => " . $info['saved'] . " salvo(s), " . $info['notSaved'] . " não salvo(s).");
    }
}
