<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Contracts;

use stdClass;

interface TipoParteRegistroFiduciarioOrdemServiceInterface
{
    /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args);
}