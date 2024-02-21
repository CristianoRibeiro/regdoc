<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_dajes;
use stdClass;

interface RegistroFiduciarioDajeServiceInterface
{
    /**
     * @param stdClass $params
     * @return registro_fiduciario_dajes
     */
    public function inserir(stdClass $params): registro_fiduciario_dajes;
}
