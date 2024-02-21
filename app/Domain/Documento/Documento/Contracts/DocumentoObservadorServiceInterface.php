<?php

namespace App\Domain\Documento\Documento\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Documento\Models\documento_observador;

interface DocumentoObservadorServiceInterface
{
    /**
     * @param int $id_documento_observador
     * @return documento_observador|null
     */
    public function buscar(int $id_documento_observador) : ?documento_observador;

    /**
     * @param stdClass $args
     * @return documento_observador
     */
    public function inserir(stdClass $args) : documento_observador;

    /**
     * @param documento_observador $documento_observador
     * @param stdClass $args
     * @return documento_observador
     */
    public function alterar(documento_observador $documento_observador, stdClass $args) : documento_observador;

    /**
     * @param documento_observador $documento_observador
     * @return bool
     */
    public function deletar(documento_observador $documento_observador) : bool;
}
