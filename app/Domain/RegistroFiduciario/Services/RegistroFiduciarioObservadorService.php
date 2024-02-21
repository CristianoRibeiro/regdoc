<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_observador;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorServiceInterface;

class RegistroFiduciarioObservadorService implements RegistroFiduciarioObservadorServiceInterface
{
    /**
     * @var RegistroFiduciarioObservadorRepositoryInterface
     */
    protected $RegistroFiduciarioObservadorRepositoryInterface;

    /**
     * RegistroFiduciarioObservadorService constructor.
     * @param RegistroFiduciarioObservadorRepositoryInterface $RegistroFiduciarioObservadorRepositoryInterface
     */
    public function __construct(RegistroFiduciarioObservadorRepositoryInterface $RegistroFiduciarioObservadorRepositoryInterface)
    {
        return $this->RegistroFiduciarioObservadorRepositoryInterface = $RegistroFiduciarioObservadorRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_observador
     * @return registro_fiduciario_observador|null
     */
    public function buscar(int $id_registro_fiduciario_observador): ?registro_fiduciario_observador
    {
        return $this->RegistroFiduciarioObservadorRepositoryInterface->buscar($id_registro_fiduciario_observador);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_observador
     */
    public function inserir(stdClass $args): registro_fiduciario_observador
    {
        return $this->RegistroFiduciarioObservadorRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_observador $registro_fiduciario_observador
     * @param stdClass $args
     * @return registro_fiduciario_observador
     */
    public function alterar(registro_fiduciario_observador $registro_fiduciario_observador, stdClass $args): registro_fiduciario_observador
    {
        return $this->RegistroFiduciarioObservadorRepositoryInterface->alterar($registro_fiduciario_observador, $args);
    }

    /**
     * @param registro_fiduciario_observador $registro_fiduciario_observador
     * @return bool
     */
    public function deletar(registro_fiduciario_observador $registro_fiduciario_observador) : bool
    {
        return $this->RegistroFiduciarioObservadorRepositoryInterface->deletar($registro_fiduciario_observador);
    }
}
