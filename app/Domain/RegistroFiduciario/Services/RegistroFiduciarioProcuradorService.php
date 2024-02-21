<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_procurador;

class RegistroFiduciarioProcuradorService implements RegistroFiduciarioProcuradorServiceInterface
{
    /**
     * @var RegistroFiduciarioProcuradorRepositoryInterface
     */
    protected $RegistroFiduciarioProcuradorRepositoryInterface;

    /**
     * RegistroFiduciarioProcuradorService constructor.
     * @param RegistroFiduciarioProcuradorRepositoryInterface $RegistroFiduciarioProcuradorRepositoryInterface
     */
    public function __construct(RegistroFiduciarioProcuradorRepositoryInterface $RegistroFiduciarioProcuradorRepositoryInterface)
    {
        $this->RegistroFiduciarioProcuradorRepositoryInterface = $RegistroFiduciarioProcuradorRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_procurador
     * @return registro_fiduciario_procurador
     */
    public function buscar_procurador(int $id_registro_fiduciario_procurador) : registro_fiduciario_procurador
    {
        return $this->RegistroFiduciarioProcuradorRepositoryInterface->buscar_procurador($id_registro_fiduciario_procurador);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_procurador
     */
    public function inserir(stdClass $args): registro_fiduciario_procurador
    {
        return $this->RegistroFiduciarioProcuradorRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_procurador $registro_fiduciario_procurador
     * @param stdClass $args
     * @return registro_fiduciario_procurador
     */
    public function alterar(registro_fiduciario_procurador $registro_fiduciario_procurador, stdClass $args): registro_fiduciario_procurador
    {
        return $this->RegistroFiduciarioProcuradorRepositoryInterface->alterar($registro_fiduciario_procurador, $args);
    }
}
