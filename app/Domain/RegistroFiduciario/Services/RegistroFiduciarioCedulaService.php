<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_cedula;

class RegistroFiduciarioCedulaService implements RegistroFiduciarioCedulaServiceInterface
{
    /**
     * @var RegistroFiduciarioCedulaRepositoryInterface
     */
    protected $RegistroFiduciarioCedulaRepositoryInterface;

    /**
     * RegistroFiduciarioCedulaService constructor.
     * @param RegistroFiduciarioCedulaRepositoryInterface $RegistroFiduciarioCedulaRepositoryInterface
     */
    public function __construct(RegistroFiduciarioCedulaRepositoryInterface $RegistroFiduciarioCedulaRepositoryInterface)
    {
        $this->RegistroFiduciarioCedulaRepositoryInterface = $RegistroFiduciarioCedulaRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_cedula
     */
    public function inserir(stdClass $args): registro_fiduciario_cedula
    {
        return $this->RegistroFiduciarioCedulaRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_cedula $registro_fiduciario_cedula
     * @param stdClass $args
     * @return registro_fiduciario_cedula
     */
    public function alterar(registro_fiduciario_cedula $registro_fiduciario_cedula, stdClass $args): registro_fiduciario_cedula
    {
        return $this->RegistroFiduciarioCedulaRepositoryInterface->alterar($registro_fiduciario_cedula, $args);
    }
}
