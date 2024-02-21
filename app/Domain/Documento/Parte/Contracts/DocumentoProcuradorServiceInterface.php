<?php

namespace App\Domain\Documento\Parte\Contracts;

use stdClass;

use App\Domain\Documento\Parte\Models\documento_procurador;

interface DocumentoProcuradorServiceInterface
{
    /**
     * @param int $id_documento_procurador
     * @return documento_procurador|null
    */
    public function buscar(int $id_documento_procurador) : ?documento_procurador;

    /**
     * @param stdClass $args
     * @return documento_procurador
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_procurador;

     /**
     * @param documento_procurador $documento_procurador
     * @param stdClass $args
     * @return documento_procurador
     * @throws Exception
     */
    public function alterar(documento_procurador $documento_procurador, stdClass $args) : documento_procurador;

    /**
     * @param stdClass $args
     * @return documento_procurador
     * @throws Exception
     */
    public function buscar_alterar(stdClass $args) : documento_procurador;
}
