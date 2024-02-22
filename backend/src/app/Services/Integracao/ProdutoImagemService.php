<?php

namespace App\Services\Integracao;

class ProdutoImagemService extends IntegracaoImagemService
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->path = PRODUTO_IMAGEM_INTEG;
    }
}