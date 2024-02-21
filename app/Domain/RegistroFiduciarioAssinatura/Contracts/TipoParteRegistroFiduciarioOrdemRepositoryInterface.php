<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Contracts;

use stdClass;

interface TipoParteRegistroFiduciarioOrdemRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args);
}