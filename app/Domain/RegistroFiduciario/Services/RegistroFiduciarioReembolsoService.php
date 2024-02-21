<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_reembolso;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoServiceInterface;

class RegistroFiduciarioReembolsoService implements RegistroFiduciarioReembolsoServiceInterface
{
    /**
     * @var RegistroFiduciarioReembolsoRepositoryInterface
     */
    protected $RegistroFiduciarioReembolsoRepositoryInterface;

    /**
     * RegistroFiduciarioReembolsoService constructor.
     * @param RegistroFiduciarioReembolsoRepositoryInterface $RegistroFiduciarioReembolsoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioReembolsoRepositoryInterface $RegistroFiduciarioReembolsoRepositoryInterface)
    {
        $this->RegistroFiduciarioReembolsoRepositoryInterface = $RegistroFiduciarioReembolsoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioReembolsoRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_reembolso_situacao
     * @return registro_fiduciario_reembolso|null
     */
    public function buscar(int $id_registro_fiduciario_reembolso_situacao): ?registro_fiduciario_reembolso
    {
        return $this->RegistroFiduciarioReembolsoRepositoryInterface->buscar($id_registro_fiduciario_reembolso_situacao);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_reembolso
     */
    public function inserir(stdClass $args): registro_fiduciario_reembolso
    {
        return $this->RegistroFiduciarioReembolsoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_reembolso $registro_fiduciario_reembolso
     * @param stdClass $args
     * @return registro_fiduciario_reembolso
     */
    public function alterar(registro_fiduciario_reembolso $registro_fiduciario_reembolso, stdClass $args) : registro_fiduciario_reembolso
    {
        return $this->RegistroFiduciarioReembolsoRepositoryInterface->alterar($registro_fiduciario_reembolso, $args);
    }
}
