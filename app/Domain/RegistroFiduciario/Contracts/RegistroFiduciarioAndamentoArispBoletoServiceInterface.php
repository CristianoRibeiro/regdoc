<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;
use App\Domain\Arisp\Models\arisp_boleto;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento_arisp_boleto;

interface RegistroFiduciarioAndamentoArispBoletoServiceInterface
{
    public function inserir_relacao(registro_fiduciario_andamento $registro_fiduciario_andamento, arisp_boleto $arisp_boleto) : registro_fiduciario_andamento_arisp_boleto;
}
