<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_conjuge;
use stdClass;

interface RegistroFiduciarioConjugeRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_conjuge
     */
    public function inserir(stdClass $args) : registro_fiduciario_conjuge;
}
