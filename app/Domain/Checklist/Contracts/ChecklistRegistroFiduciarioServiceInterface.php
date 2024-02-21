<?php

namespace App\Domain\Checklist\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Checklist\Models\checklist_registro_fiduciario;

interface ChecklistRegistroFiduciarioServiceInterface
{
    /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args);

    /**
     * @param int $id_checklist_registro_fiduciario
     * @return checklist_registro_fiduciario|null
     */
    public function buscar(int $id_checklist_registro_fiduciario) : ?checklist_registro_fiduciario;

    /**
     * @param stdClass $args
     * @return checklist_registro_fiduciario
     */
    public function inserir(stdClass $args) : checklist_registro_fiduciario;

    /**
     * @param checklist_registro_fiduciario $checklist_registro_fiduciario
     * @param stdClass $args
     * @return checklist_registro_fiduciario
     */
    public function alterar(checklist_registro_fiduciario $checklist_registro_fiduciario, stdClass $args) : checklist_registro_fiduciario;
}
