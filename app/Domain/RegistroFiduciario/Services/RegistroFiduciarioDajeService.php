<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioDajeRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioDajeServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_dajes;

class RegistroFiduciarioDajeService implements RegistroFiduciarioDajeServiceInterface
{
    /**
     * @var RegistroFiduciarioDajeRepositoryInterface
     */
    protected $RegistroFiduciarioDajeRepositoryInterface;

    /**
     * RegistroFiduciarioDajeService constructor.
     * @param RegistroFiduciarioDajeRepositoryInterface $RegistroFiduciarioDajeRepositoryInterface
     */
    public function __construct(RegistroFiduciarioDajeRepositoryInterface $RegistroFiduciarioDajeRepositoryInterface)
    {
        $this->RegistroFiduciarioDajeRepositoryInterface = $RegistroFiduciarioDajeRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_dajes
     */
    public function inserir(stdClass $args): registro_fiduciario_dajes
    {
        return $this->RegistroFiduciarioDajeRepositoryInterface->inserir($args);
    }
}
