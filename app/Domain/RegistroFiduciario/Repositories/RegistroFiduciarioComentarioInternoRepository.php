<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioInternoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_comentario_interno;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use Ramsey\Uuid\Uuid;

use stdClass;
use Exception;

class RegistroFiduciarioComentarioInternoRepository implements RegistroFiduciarioComentarioInternoRepositoryInterface
{
    public function buscar(int $id_registro_fiduciario_comentario): ?registro_fiduciario_comentario_interno
    {
        return registro_fiduciario_comentario_interno::findOrFail($id_registro_fiduciario_comentario);
    }


    public function buscar_uuid(string $uuid): ?registro_fiduciario_comentario_interno
    {
        return registro_fiduciario_comentario_interno::where('uuid', $uuid)->firstOrFail();
    }

    public function inserir(stdClass $args): registro_fiduciario_comentario_interno
    {
        $comentarioInterno = new registro_fiduciario_comentario_interno();
        $comentarioInterno->uuid = Uuid::uuid4();
        $comentarioInterno->id_registro_fiduciario = $args->id_registro_fiduciario;
        $comentarioInterno->de_comentario = $args->de_comentario;
        $comentarioInterno->id_usuario_cad = Auth::User()->id_usuario;
        $comentarioInterno->in_direcao = $args->in_direcao;

        if (!$comentarioInterno->save()) {
            throw new Exception('Erro ao salvar o comentário do registro fiduciário.');
        }

        return $comentarioInterno;
    }

    public function alterar(registro_fiduciario_comentario_interno $comentarioInterno, stdClass $args): registro_fiduciario_comentario_interno
    {
        $comentarioInterno->de_comentario = $args->de_comentario;
        $comentarioInterno->dt_atualizacao =  Carbon::now();

        if (!$comentarioInterno->save()) {
            throw new Exception('Erro ao atualizar o comentário do registro fiduciario.');
        }

        return $comentarioInterno;
    }
}