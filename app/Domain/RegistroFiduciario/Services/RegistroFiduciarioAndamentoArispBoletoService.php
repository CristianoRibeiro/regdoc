<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoArispBoletoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioAndamentoArispBoletoRepository;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;
use App\Domain\Arisp\Models\arisp_boleto;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento_arisp_boleto;

class RegistroFiduciarioAndamentoArispBoletoService implements RegistroFiduciarioAndamentoArispBoletoServiceInterface
{
    public function __construct(RegistroFiduciarioAndamentoArispBoletoRepository $RegistroFiduciarioAndamentoArispBoletoRepository)
    {
        $this->RegistroFiduciarioAndamentoArispBoletoRepository = $RegistroFiduciarioAndamentoArispBoletoRepository;
    }

    public function inserir_relacao(registro_fiduciario_andamento $registro_fiduciario_andamento, arisp_boleto $arisp_boleto) : registro_fiduciario_andamento_arisp_boleto
    {
        return $this->RegistroFiduciarioAndamentoArispBoletoRepository->inserir_relacao($registro_fiduciario_andamento, $arisp_boleto);
    }
}
