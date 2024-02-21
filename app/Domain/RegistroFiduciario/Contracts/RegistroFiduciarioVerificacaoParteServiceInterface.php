<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_verificacoes_parte;
use stdClass;

interface RegistroFiduciarioVerificacaoParteServiceInterface
{
    /**
     * @param stdClass $params
     * @return registro_fiduciario_verificacoes_parte
     */
    public function inserir(stdClass $params): registro_fiduciario_verificacoes_parte;
}
