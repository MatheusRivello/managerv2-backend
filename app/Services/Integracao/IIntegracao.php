<?php

namespace App\Services\Integracao;

interface IIntegracao {
    public function request();
    public function getLog();
}