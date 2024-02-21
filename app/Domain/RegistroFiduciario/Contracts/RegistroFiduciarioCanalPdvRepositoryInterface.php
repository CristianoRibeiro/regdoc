<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_canal_pdv_parceiro;
use stdClass;

interface RegistroFiduciarioCanalPdvRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_canal_pdv_parceiro
     */
    public function inserir(stdClass $args): registro_fiduciario_canal_pdv_parceiro;
}
