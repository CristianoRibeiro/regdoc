<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioChecklistRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioChecklistServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_checklist;

class RegistroFiduciarioChecklistService implements RegistroFiduciarioChecklistServiceInterface
{
    /**
     * @var RegistroFiduciarioChecklistRepositoryInterface
     */
    protected $RegistroFiduciarioChecklistRepositoryInterface;

    /**
     * RegistroFiduciarioChecklistService constructor.
     * @param RegistroFiduciarioChecklistRepositoryInterface $RegistroFiduciarioChecklistRepositoryInterface
     */
    public function __construct(RegistroFiduciarioChecklistRepositoryInterface $RegistroFiduciarioChecklistRepositoryInterface)
    {
        $this->RegistroFiduciarioChecklistRepositoryInterface = $RegistroFiduciarioChecklistRepositoryInterface;
    }

  /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioChecklistRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_checklist
     * @return registro_fiduciario_checklist|null
     */
    public function buscar(int $id_registro_fiduciario_checklist): ?registro_fiduciario_checklist
    {
        return $this->RegistroFiduciarioChecklistRepositoryInterface->buscar($id_registro_fiduciario_checklist);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_checklist
     */
    public function inserir(stdClass $args): registro_fiduciario_checklist
    {
        return $this->RegistroFiduciarioChecklistRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_checklist $registro_fiduciario_checklist
     * @param stdClass $args
     * @return registro_fiduciario_checklist
     */
    public function alterar(registro_fiduciario_checklist $registro_fiduciario_checklist, stdClass $args): registro_fiduciario_checklist
    {
        return $this->RegistroFiduciarioChecklistRepositoryInterface->alterar($registro_fiduciario_checklist, $args);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_checklist
     */
    public function buscar_alterar(stdClass $args): registro_fiduciario_checklist
    {
        $registro_fiduciario_checklist = $this->buscar($args->id_registro_fiduciario_checklist);
        if (!$registro_fiduciario_checklist)
            throw new Exception('A checklist não foi encontrada');

        return $this->alterar($registro_fiduciario_checklist, $args);
    }
}
