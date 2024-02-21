<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCanalPdvRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCanalPdvServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_canal_pdv_parceiro;

use stdClass;

class RegistroFiduciarioCanalPdvService implements  RegistroFiduciarioCanalPdvServiceInterface
{
    protected $RegistroFiduciarioCanalPdvRepositoryInterface;

    public function __construct(RegistroFiduciarioCanalPdvRepositoryInterface $RegistroFiduciarioCanalPdvRepositoryInterface)
    {
        $this->RegistroFiduciarioCanalPdvRepositoryInterface = $RegistroFiduciarioCanalPdvRepositoryInterface;
    }

    public function inserir(stdClass $args) : registro_fiduciario_canal_pdv_parceiro
    {
        return $this->RegistroFiduciarioCanalPdvRepositoryInterface->inserir($args);
    }
}
