<?php

namespace App\Domain\Documento\Assinatura\Services;

use stdClass;

use App\Domain\Documento\Assinatura\Models\documento_parte_assinatura;

use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaRepositoryInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaServiceInterface;

class DocumentoParteAssinaturaService implements DocumentoParteAssinaturaServiceInterface
{
    /**
     * @var DocumentoParteAssinaturaRepositoryInterface;
     */
    protected $DocumentoParteAssinaturaRepositoryInterface;

    /**
     * DocumentoParteAssinaturaService constructor.
     * @param DocumentoParteAssinaturaRepositoryInterface $DocumentoParteAssinaturaRepositoryInterface
     */
    public function __construct(DocumentoParteAssinaturaRepositoryInterface $DocumentoParteAssinaturaRepositoryInterface)
    {
        $this->DocumentoParteAssinaturaRepositoryInterface = $DocumentoParteAssinaturaRepositoryInterface;
    }


    /**
     * @param int $id_documento_parte_assinatura
     * @return documento_parte_assinatura|null
     */
    public function buscar(int $id_documento_parte_assinatura): ?documento_parte_assinatura
    {
        return $this->DocumentoParteAssinaturaRepositoryInterface->buscar($id_documento_parte_assinatura);
    }


    /**
     * @param stdClass $args
     * @return documento_parte_assinatura
    */
    public function inserir(stdClass $args): documento_parte_assinatura
    {
        return $this->DocumentoParteAssinaturaRepositoryInterface->inserir($args);
    }


    /**
     * @param documento_parte_assinatura $documento_parte_assinatura
     * @param stdClass $args
     * @return documento_parte_assinatura
     */
    public function alterar(documento_parte_assinatura $documento_parte_assinatura, stdClass $args): documento_parte_assinatura
    {
        return $this->DocumentoParteAssinaturaRepositoryInterface->alterar($documento_parte_assinatura, $args);
    }


    /**
     * @param stdClass $args
     * @return documento_parte_assinatura
     */
    public function buscar_alterar(stdClass $args): documento_parte_assinatura
    {
        return $this->DocumentoParteAssinaturaRepositoryInterface->buscar_alterar($args);
    }






}
