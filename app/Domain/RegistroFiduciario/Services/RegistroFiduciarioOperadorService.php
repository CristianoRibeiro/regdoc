<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_operador;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperadorRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperadorServiceInterface;

class RegistroFiduciarioOperadorService implements RegistroFiduciarioOperadorServiceInterface
{
    /**
     * @var RegistroFiduciarioOperadorRepositoryInterface
     */
    protected $RegistroFiduciarioOperadorRepositoryInterface;

    /**
     * RegistroFiduciarioOperadorService constructor.
     * @param RegistroFiduciarioOperadorRepositoryInterface $RegistroFiduciarioOperadorRepositoryInterface
     */
    public function __construct(RegistroFiduciarioOperadorRepositoryInterface $RegistroFiduciarioOperadorRepositoryInterface)
    {
        return $this->RegistroFiduciarioOperadorRepositoryInterface = $RegistroFiduciarioOperadorRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_operador
     * @return registro_fiduciario_operador|null
     */
    public function buscar(int $id_registro_fiduciario_operador): ?registro_fiduciario_operador
    {
        return $this->RegistroFiduciarioOperadorRepositoryInterface->buscar($id_registro_fiduciario_operador);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_operador
     */
    public function inserir(stdClass $args): registro_fiduciario_operador
    {
        return $this->RegistroFiduciarioOperadorRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_operador $registro_fiduciario_operador
     * @param stdClass $args
     * @return registro_fiduciario_operador
     */
    public function alterar(registro_fiduciario_operador $registro_fiduciario_operador, stdClass $args): registro_fiduciario_operador
    {
        return $this->RegistroFiduciarioOperadorRepositoryInterface->alterar($registro_fiduciario_operador, $args);
    }

    /**
     * @param registro_fiduciario_operador $registro_fiduciario_operador
     * @return registro_fiduciario_operador
     */
    public function deletar(registro_fiduciario_operador $registro_fiduciario_operador) : registro_fiduciario_operador
    {
        return $this->RegistroFiduciarioOperadorRepositoryInterface->deletar($registro_fiduciario_operador);
    }
}
