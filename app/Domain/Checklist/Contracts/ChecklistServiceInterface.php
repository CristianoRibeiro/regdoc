<?php

namespace App\Domain\Checklist\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Checklist\Models\checklist;

interface ChecklistServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_checklist
     * @return checklist|null
     */
    public function buscar(int $id_checklist) : ?checklist;

    /**
     * @param stdClass $args
     * @return checklist
     */
    public function inserir(stdClass $args) : checklist;

    /**
     * @param checklist $checklist
     * @param stdClass $args
     * @return checklist
     */
    public function alterar(checklist $checklist, stdClass $args) : checklist;
}
