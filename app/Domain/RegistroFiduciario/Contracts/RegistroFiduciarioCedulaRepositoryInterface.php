<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_cedula;
use stdClass;

interface RegistroFiduciarioCedulaRepositoryInterface
{
    /**
     * @param stdClass $params
     * @return registro_fiduciario_cedula
     */
    public function inserir(stdClass $params): registro_fiduciario_cedula;

    /**
     * @param registro_fiduciario_cedula $registro_fiduciario_cedula
     * @param stdClass $args
     * @return registro_fiduciario_cedula
     */
    public function alterar(registro_fiduciario_cedula $registro_fiduciario_cedula, stdClass $args): registro_fiduciario_cedula;
}
