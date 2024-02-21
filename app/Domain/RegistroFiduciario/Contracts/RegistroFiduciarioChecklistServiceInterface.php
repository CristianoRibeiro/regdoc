<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use  App\Domain\RegistroFiduciario\Models\registro_fiduciario_checklist;

interface RegistroFiduciarioChecklistServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_checklist
     * @return registro_fiduciario_checklist|null
     */
    public function buscar(int $id_registro_fiduciario_checklist) : ?registro_fiduciario_checklist;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_checklist
     */
    public function inserir(stdClass $args) : registro_fiduciario_checklist;

    /**
     * @param registro_fiduciario_checklist $registro_fiduciario_checklist
     * @param stdClass $args
     * @return registro_fiduciario_checklist
     */
    public function alterar(registro_fiduciario_checklist $registro_fiduciario_checklist, stdClass $args) : registro_fiduciario_checklist;
}
