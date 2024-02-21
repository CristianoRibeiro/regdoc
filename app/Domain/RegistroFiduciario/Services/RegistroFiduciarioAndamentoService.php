<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;
use stdClass;

class RegistroFiduciarioAndamentoService implements RegistroFiduciarioAndamentoServiceInterface
{
    protected $RegistroFiduciarioAndamentoRepositoryInterface;

    public function __construct(RegistroFiduciarioAndamentoRepositoryInterface $RegistroFiduciarioAndamentoRepositoryInterface)
    {
        $this->RegistroFiduciarioAndamentoRepositoryInterface = $RegistroFiduciarioAndamentoRepositoryInterface;
    }

    public function inserir_andamento(registro_fiduciario $registro_fiduciario, stdClass $args) : registro_fiduciario_andamento
    {
        return $this->RegistroFiduciarioAndamentoRepositoryInterface->inserir_andamento($registro_fiduciario, $args);
    }
}
