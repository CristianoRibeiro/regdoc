<?php

namespace App\Domain\Documento\Parte\Contracts;

use stdClass;

use App\Domain\Documento\Parte\Models\documento_parte;

interface DocumentoParteServiceInterface
{
    /**
     * @param int $id_documento_parte
     * @return documento_parte|null
    */
    public function buscar(int $id_documento_parte) : ?documento_parte;

    /**
     * @param string $uuid
     * @return documento_parte|null
     */
    public function buscar_uuid(string $uuid) : ?documento_parte;

    /**
     * @param stdClass $args
     * @return documento_parte
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_parte;

     /**
     * @param documento_parte $documento_parte
     * @param stdClass $args
     * @return documento_parte
     * @throws Exception
     */
    public function alterar(documento_parte $documento_parte, stdClass $args) : documento_parte;

    /**
     * @param stdClass $args
     * @return documento_parte
     * @throws Exception
     */
    public function buscar_alterar(stdClass $args) : documento_parte;
}
