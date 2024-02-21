<?php

namespace App\Domain\Documento\Assinatura\Services;

use stdClass;

use App\Domain\Documento\Assinatura\Models\documento_parte_assinatura_arquivo;

use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaArquivoRepositoryInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaArquivoServiceInterface;

class DocumentoParteAssinaturaArquivoService implements DocumentoParteAssinaturaArquivoServiceInterface
{
    /**
     * @var DocumentoParteAssinaturaArquivoRepositoryInterface;
     */
    protected $DocumentoParteAssinaturaArquivoRepositoryInterface;

    /**
     * DocumentoParteAssinaturaArquivoService constructor.
     * @param DocumentoParteAssinaturaArquivoRepositoryInterface $DocumentoParteAssinaturaArquivoRepositoryInterface
     */
    public function __construct(DocumentoParteAssinaturaArquivoRepositoryInterface $DocumentoParteAssinaturaArquivoRepositoryInterface)
    {
        $this->DocumentoParteAssinaturaArquivoRepositoryInterface = $DocumentoParteAssinaturaArquivoRepositoryInterface;
    }


    /**
     * @param int $id_documento_parte_assinatura_arquivo
     * @return documento_parte_assinatura_arquivo|null
     */
    public function buscar(int $id_documento_parte_assinatura_arquivo): ?documento_parte_assinatura_arquivo
    {
        return $this->DocumentoParteAssinaturaArquivoRepositoryInterface->buscar($id_documento_parte_assinatura_arquivo);
    }


    /**
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
    */
    public function inserir(stdClass $args): documento_parte_assinatura_arquivo
    {
        return $this->DocumentoParteAssinaturaArquivoRepositoryInterface->inserir($args);
    }


    /**
     * @param documento_parte_assinatura_arquivo $documento_parte_assinatura_arquivo
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
     */
    public function alterar(documento_parte_assinatura_arquivo $documento_parte_assinatura_arquivo, stdClass $args): documento_parte_assinatura_arquivo
    {
        return $this->DocumentoParteAssinaturaArquivoRepositoryInterface->alterar($documento_parte_assinatura_arquivo, $args);
    }


    /**
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
     */
    public function buscar_alterar(stdClass $args): documento_parte_assinatura_arquivo
    {
        return $this->DocumentoParteAssinaturaArquivoRepositoryInterface->buscar_alterar($args);
    }






}
