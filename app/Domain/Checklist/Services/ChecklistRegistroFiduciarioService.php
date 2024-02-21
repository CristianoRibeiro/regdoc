<?php

namespace App\Domain\Checklist\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Checklist\Models\checklist_registro_fiduciario;

use App\Domain\Checklist\Contracts\ChecklistRegistroFiduciarioRepositoryInterface;
use App\Domain\Checklist\Contracts\ChecklistRegistroFiduciarioServiceInterface;

class ChecklistRegistroFiduciarioService implements ChecklistRegistroFiduciarioServiceInterface
{
    /**
     * @var ChecklistRegistroFiduciarioRepositoryInterface
     */
    protected $ChecklistRegistroFiduciarioRepositoryInterface;

    /**
     * ChecklistRegistroFiduciarioService constructor.
     * @param ChecklistRegistroFiduciarioRepositoryInterface $ChecklistRegistroFiduciarioRepositoryInterface
     */
    public function __construct(ChecklistRegistroFiduciarioRepositoryInterface $ChecklistRegistroFiduciarioRepositoryInterface)
    {
        $this->ChecklistRegistroFiduciarioRepositoryInterface = $ChecklistRegistroFiduciarioRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args)
    {
        return $this->ChecklistRegistroFiduciarioRepositoryInterface->listar($args);
    }

    /**
     * @param int $id_checklist_registro_fiduciario
     * @return checklist_registro_fiduciario|null
     */
    public function buscar(int $id_checklist_registro_fiduciario): ?checklist_registro_fiduciario
    {
        return $this->ChecklistRegistroFiduciarioRepositoryInterface->buscar($id_checklist_registro_fiduciario);
    }

    /**
     * @param stdClass $args
     * @return checklist_registro_fiduciario
     */
    public function inserir(stdClass $args): checklist_registro_fiduciario
    {
        return $this->ChecklistRegistroFiduciarioRepositoryInterface->inserir($args);
    }

    /**
     * @param checklist_registro_fiduciario $checklist_registro_fiduciario
     * @param stdClass $args
     * @return checklist_registro_fiduciario
     */
    public function alterar(checklist_registro_fiduciario $checklist_registro_fiduciario, stdClass $args) : checklist_registro_fiduciario
    {
        return $this->ChecklistRegistroFiduciarioRepositoryInterface->alterar($checklist_registro_fiduciario, $args);
    }
}
