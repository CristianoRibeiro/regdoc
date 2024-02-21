<?php

namespace App\Domain\Checklist\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Checklist\Models\checklist;

use App\Domain\Checklist\Contracts\ChecklistRepositoryInterface;
use App\Domain\Checklist\Contracts\ChecklistServiceInterface;

class ChecklistService implements ChecklistServiceInterface
{
    /**
     * @var ChecklistRepositoryInterface
     */
    protected $ChecklistRepositoryInterface;

    /**
     * ChecklistService constructor.
     * @param ChecklistRepositoryInterface $ChecklistRepositoryInterface
     */
    public function __construct(ChecklistRepositoryInterface $ChecklistRepositoryInterface)
    {
        $this->ChecklistRepositoryInterface = $ChecklistRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->ChecklistRepositoryInterface->listar();
    }

    /**
     * @param int $id_checklist
     * @return checklist|null
     */
    public function buscar(int $id_checklist): ?checklist
    {
        return $this->ChecklistRepositoryInterface->buscar($id_checklist);
    }

    /**
     * @param stdClass $args
     * @return checklist
     */
    public function inserir(stdClass $args): checklist
    {
        return $this->ChecklistRepositoryInterface->inserir($args);
    }

    /**
     * @param checklist $checklist
     * @param stdClass $args
     * @return checklist
     */
    public function alterar(checklist $checklist, stdClass $args) : checklist
    {
        return $this->ChecklistRepositoryInterface->alterar($checklist, $args);
    }
}
