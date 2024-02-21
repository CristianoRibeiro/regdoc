<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Auth;
use Exception;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_checklist;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioChecklistRepositoryInterface;

class RegistroFiduciarioChecklistRepository implements RegistroFiduciarioChecklistRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_checklist::orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_checklist
     * @return registro_fiduciario_checklist|null
     */
    public function buscar(int $id_registro_fiduciario_checklist): ?registro_fiduciario_checklist
    {
        return registro_fiduciario_checklist::find($id_registro_fiduciario_checklist);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_checklist
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_checklist
    {
        $registro_fiduciario_checklist = new registro_fiduciario_checklist();
        $registro_fiduciario_checklist->id_registro_fiduciario = $args->id_registro_fiduciario;
        $registro_fiduciario_checklist->id_checklist = $args->id_checklist;
        $registro_fiduciario_checklist->in_marcado = $args->in_marcado ?? NULL;
        $registro_fiduciario_checklist->nu_ordem = $args->nu_ordem ?? NULL;
        $registro_fiduciario_checklist->id_usuario_cad = Auth::User()->id_usuario;

        if (!$registro_fiduciario_checklist->save()) {
            throw new Exception('Erro ao salvar a checklist do registro.');
        }

        return $registro_fiduciario_checklist;
    }

    /**
     * @param registro_fiduciario_checklist $registro_fiduciario_checklist
     * @param stdClass $args
     * @return registro_fiduciario_checklist
     * @throws Exception
     */
    public function alterar(registro_fiduciario_checklist $registro_fiduciario_checklist, stdClass $args): registro_fiduciario_checklist
    {
        if (isset($args->in_marcado)) {
            $registro_fiduciario_checklist->in_marcado = $args->in_marcado;
        }

        if (!$registro_fiduciario_checklist->save()) {
            throw new Exception('Erro ao atualizar a checklist do registro.');
        }

        $registro_fiduciario_checklist->refresh();

        return $registro_fiduciario_checklist;
    }
}
