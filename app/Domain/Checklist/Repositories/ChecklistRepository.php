<?php

namespace App\Domain\Checklist\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;

use App\Domain\Checklist\Models\checklist;

use App\Domain\Checklist\Contracts\ChecklistRepositoryInterface;

class ChecklistRepository implements ChecklistRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return checklist::orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_checklist
     * @return checklist|null
     */
    public function buscar(int $id_checklist) : ?checklist
    {
        return checklist::find($id_checklist);
    }

    /**
     * @param checklist $checklist
     * @param stdClass $args
     * @return checklist
     * @throws Exception
     */
    public function inserir(stdClass $args) : checklist
    {
        $novo_checklist = new checklist();
        $novo_checklist->no_checklist = $args->no_checklist;
        $novo_checklist->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        if (!$novo_checklist->save()) {
            throw new Exception('Erro ao salvar o checklist.');
        }

        return $novo_checklist;
    }

    /**
     * @param checklist $checklist
     * @param stdClass $args
     * @return checklist
     * @throws Exception
     */
    public function alterar(checklist $checklist, stdClass $args) : checklist
    {
        if (isset($args->no_checklist)) {
            $checklist->no_checklist = $args->no_checklist;
        }

        if (!$checklist->save()) {
            throw new  Exception('Erro ao atualizar o checklist.');
        }

        $checklist->refresh();

        return $checklist;
    }
}
