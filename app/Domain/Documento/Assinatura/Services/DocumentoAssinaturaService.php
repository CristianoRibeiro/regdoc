<?php

namespace App\Domain\Documento\Assinatura\Services;

use stdClass;

use App\Domain\Documento\Assinatura\Models\documento_assinatura;

use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaRepositoryInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaServiceInterface;

class DocumentoAssinaturaService implements DocumentoAssinaturaServiceInterface
{
    /**
     * @var DocumentoAssinaturaRepositoryInterface;
     */
    protected $DocumentoAssinaturaRepositoryInterface;

    /**
     * DocumentoAssinaturaService constructor.
     * @param DocumentoAssinaturaRepositoryInterface $DocumentoAssinaturaRepositoryInterface
     */
    public function __construct(DocumentoAssinaturaRepositoryInterface $DocumentoAssinaturaRepositoryInterface)
    {
        $this->DocumentoAssinaturaRepositoryInterface = $DocumentoAssinaturaRepositoryInterface;
    }


    /**
     * @param int $id_documento_assinatura
     * @return documento_assinatura|null
     */
    public function buscar(int $id_documento_assinatura): ?documento_assinatura
    {
        return $this->DocumentoAssinaturaRepositoryInterface->buscar($id_documento_assinatura);
    }


    /**
     * @param string $uuid
     * @return documento_assinatura|null
     */
    public function buscar_pdavh_uuid(string $uuid): ?documento_assinatura
    {
        return $this->DocumentoAssinaturaRepositoryInterface->buscar_pdavh_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return documento_assinatura
    */
    public function inserir(stdClass $args): documento_assinatura
    {
        return $this->DocumentoAssinaturaRepositoryInterface->inserir($args);
    }


    /**
     * @param documento_assinatura $documento_assinatura
     * @param stdClass $args
     * @return documento_assinatura
     */
    public function alterar(documento_assinatura $documento_assinatura, stdClass $args): documento_assinatura
    {
        return $this->DocumentoAssinaturaRepositoryInterface->alterar($documento_assinatura, $args);
    }


    /**
     * @param stdClass $args
     * @return documento_assinatura
     */
    public function buscar_alterar(stdClass $args): documento_assinatura
    {
        return $this->DocumentoAssinaturaRepositoryInterface->buscar_alterar($args);
    }






}
