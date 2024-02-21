<?php

namespace App\Domain\Documento\Documento\Repositories;

use Auth;
use stdClass;
use Exception;
use Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Documento\Contracts\DocumentoComentarioRepositoryInterface;
use App\Domain\Documento\Documento\Models\documento_comentario;

class DocumentoComentarioRepository implements DocumentoComentarioRepositoryInterface
{
    /**
     * @param int $id_documento_comentario
     * @return documento_comentario|null
     */
    public function buscar(int $id_documento_comentario): ?documento_comentario
    {
        return documento_comentario::find($id_documento_comentario);
    }

    /**
     * @param stdClass $args
     * @return documento_comentario
     * @throws Exception
     */
    public function inserir(stdClass $args): documento_comentario
    {
        $novo_comentario = new documento_comentario();
        $novo_comentario->uuid = Uuid::uuid4();
        $novo_comentario->id_documento = $args->id_documento;
        $novo_comentario->de_comentario = $args->de_comentario;
        $novo_comentario->id_usuario_cad = Auth::User()->id_usuario;
        $novo_comentario->in_direcao = $args->in_direcao;

        if (!$novo_comentario->save()) {
            throw new Exception('Erro ao salvar o comentário do registro fiduciário.');
        }

        return $novo_comentario;
    }

    /**
     * @param documento_comentario $documento_comentario
     * @param stdClass $args
     * @return documento_comentario
     * @throws Exception
     */
    public function alterar(documento_comentario $documento_comentario, stdClass $args): documento_comentario
    {
        $documento_comentario->de_comentario = $args->de_comentario;

        if ($documento_comentario->save()) {
            throw new Exception('Erro ao atualizar o comentário do registro fiduciario.');
        }

        return $documento_comentario;
    }
}
