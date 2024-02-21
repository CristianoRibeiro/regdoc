<?php

namespace App\Domain\Documento\Assinatura\Contracts;

use stdClass;

use App\Domain\Documento\Assinatura\Models\documento_parte_assinatura_arquivo;

interface DocumentoParteAssinaturaArquivoRepositoryInterface
{
    /**
     * @param int $id_documento_parte_assinatura_arquivo
     * @return documento_parte_assinatura_arquivo|null
     */
    public function buscar(int $id_documento_parte_assinatura_arquivo) : ?documento_parte_assinatura_arquivo;

    /**
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_parte_assinatura_arquivo;

     /**
     * @param documento_parte_assinatura_arquivo $documento_parte_assinatura_arquivo
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
     * @throws Exception
     */
    public function alterar(documento_parte_assinatura_arquivo $documento_parte_assinatura_arquivo, stdClass $args) : documento_parte_assinatura_arquivo;

    /**
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
     * @throws Exception
     */
    public function buscar_alterar(stdClass $args) : documento_parte_assinatura_arquivo;
}
