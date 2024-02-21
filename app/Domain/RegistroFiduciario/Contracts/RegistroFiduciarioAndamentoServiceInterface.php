<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;
use stdClass;

interface RegistroFiduciarioAndamentoServiceInterface
{
    public function inserir_andamento(registro_fiduciario $registro_fiduciario, stdClass $args) : registro_fiduciario_andamento;
}
