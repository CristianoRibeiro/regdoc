<?php

namespace App\Domain\Documento\Documento\Contracts;

use stdClass;

use App\Domain\Documento\Documento\Models\documento_comentario;

interface DocumentoComentarioRepositoryInterface
{
    /**
     * @param int $id_documento_comentario
     * @return documento_comentario|null
     */
    public function buscar(int $id_documento_comentario) : ?documento_comentario;

    /**
     * @param stdClass $args
     * @return documento_comentario
     */
    public function inserir(stdClass $args) : documento_comentario;

    /**
     * @param documento_comentario $documento_comentario
     * @param stdClass $args
     * @return documento_comentario
     */
    public function alterar(documento_comentario $documento_comentario, stdClass $args) : documento_comentario;
}
