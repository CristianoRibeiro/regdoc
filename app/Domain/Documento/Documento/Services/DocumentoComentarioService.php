<?php

namespace App\Domain\Documento\Documento\Services;

use stdClass;

use App\Domain\Documento\Documento\Models\documento_comentario;

use App\Domain\Documento\Documento\Contracts\DocumentoComentarioRepositoryInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoComentarioServiceInterface;

class DocumentoComentarioService implements DocumentoComentarioServiceInterface
{
    /**
     * @var DocumentoComentarioRepositoryInterface
     */
    protected $DocumentoComentarioRepositoryInterface;

    /**
     * DocumentoComentarioService constructor.
     * @param DocumentoComentarioRepositoryInterface $DocumentoComentarioRepositoryInterface
     */
    public function __construct(DocumentoComentarioRepositoryInterface $DocumentoComentarioRepositoryInterface)
    {
        return $this->DocumentoComentarioRepositoryInterface = $DocumentoComentarioRepositoryInterface;
    }

    /**
     * @param int $id_documento_comentario
     * @return documento_comentario|null
     */
    public function buscar(int $id_documento_comentario): ?documento_comentario
    {
        return $this->DocumentoComentarioRepositoryInterface->buscar($id_documento_comentario);
    }

    /**
     * @param stdClass $args
     * @return documento_comentario
     */
    public function inserir(stdClass $args): documento_comentario
    {
        return $this->DocumentoComentarioRepositoryInterface->inserir($args);
    }

    /**
     * @param int $documento_comentario
     * @param stdClass $args
     * @return documento_comentario
     */
    public function alterar(documento_comentario $documento_comentario, stdClass $args): documento_comentario
    {
        return $this->DocumentoComentarioRepositoryInterface->alterar($documento_comentario, $args);
    }
}
