<?php

namespace App\Domain\Integracao\Contracts;

use stdClass;

interface IntegracaoRegistroFiduciarioRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return int
     */
    public function definir_integracao(stdClass $args) : int;

}
