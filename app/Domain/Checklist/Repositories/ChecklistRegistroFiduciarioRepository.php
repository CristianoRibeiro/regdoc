<?php

namespace App\Domain\Checklist\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;

use App\Domain\Checklist\Models\checklist_registro_fiduciario;

use App\Domain\Checklist\Contracts\ChecklistRegistroFiduciarioRepositoryInterface;

class ChecklistRegistroFiduciarioRepository implements ChecklistRegistroFiduciarioRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args)
    {
        for($i=0;$i<5;$i++) {

            $checklist_registro_fiduciario = checklist_registro_fiduciario::join('checklist', 'checklist.id_checklist', '=', 'checklist_registro_fiduciario.id_checklist');
            if (is_null($args->id_registro_fiduciario_tipo ?? NULL)) {
                $checklist_registro_fiduciario = $checklist_registro_fiduciario->whereNull('id_registro_fiduciario_tipo');
            } else {
                $checklist_registro_fiduciario = $checklist_registro_fiduciario->where('id_registro_fiduciario_tipo', $args->id_registro_fiduciario_tipo);
            }
            if (is_null($args->id_integracao ?? NULL)) {
                $checklist_registro_fiduciario = $checklist_registro_fiduciario->whereNull('id_integracao');
            } else {
                $checklist_registro_fiduciario = $checklist_registro_fiduciario->where('id_integracao', $args->id_integracao);
            }
            if (is_null($args->id_serventia ?? NULL)) {
                $checklist_registro_fiduciario = $checklist_registro_fiduciario->whereNull('id_serventia');
            } else {
                $checklist_registro_fiduciario = $checklist_registro_fiduciario->where('id_serventia', $args->id_serventia);
            }
            if (is_null($args->id_pessoa ?? NULL)) {
                $checklist_registro_fiduciario = $checklist_registro_fiduciario->whereNull('id_pessoa');
            } else {
                $checklist_registro_fiduciario = $checklist_registro_fiduciario->where('id_pessoa', $args->id_pessoa);
            }

            $checklist_registro_fiduciario = $checklist_registro_fiduciario->orderBy('checklist.nu_ordem', 'ASC')
                ->get();

            if (count($checklist_registro_fiduciario)>0) {
                return $checklist_registro_fiduciario;
            } else {
                end($args);
                $ultima_key = key($args);

                if ($ultima_key) {
                    unset($args->$ultima_key);
                } else {
                    return [];
                }
            }
        }

    }

    /**
     * @param int $id_checklist_registro_fiduciario
     * @return checklist_registro_fiduciario|null
     */
    public function buscar(int $id_checklist_registro_fiduciario) : ?checklist_registro_fiduciario
    {
        return checklist_registro_fiduciario::find($id_checklist_registro_fiduciario);
    }

    /**
     * @param checklist_registro_fiduciario $checklist_registro_fiduciario
     * @param stdClass $args
     * @return checklist_registro_fiduciario
     * @throws Exception
     */
    public function inserir(stdClass $args) : checklist_registro_fiduciario
    {
        $novo_checklist_registro_fiduciario = new checklist_registro_fiduciario();
        $novo_checklist_registro_fiduciario->id_checklist = $args->id_checklist;
        $novo_checklist_registro_fiduciario->id_registro_fiduciario_tipo = $args->id_registro_fiduciario_tipo;
        $novo_checklist_registro_fiduciario->id_integracao = $args->id_integracao;
        $novo_checklist_registro_fiduciario->id_pessoa = $args->id_pessoa;
        $novo_checklist_registro_fiduciario->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        if (!$novo_checklist_registro_fiduciario->save()) {
            throw new Exception('Erro ao salvar o checklist registro fiduciario.');
        }

        return $novo_pagamento;
    }

    /**
     * @param checklist_registro_fiduciario $checklist_registro_fiduciario
     * @param stdClass $args
     * @return checklist_registro_fiduciario
     * @throws Exception
     */
    public function alterar(checklist_registro_fiduciario $checklist_registro_fiduciario, stdClass $args) : checklist_registro_fiduciario
    {
        if (isset($args->no_checklist_registro_fiduciario)) {
            $checklist_registro_fiduciario->no_checklist_registro_fiduciario = $args->no_checklist_registro_fiduciario;
        }

        if (!$checklist_registro_fiduciario->save()) {
            throw new  Exception('Erro ao atualizar o checklist registro fiduciario.');
        }

        $checklist_registro_fiduciario->refresh();

        return $checklist_registro_fiduciario;
    }
}
