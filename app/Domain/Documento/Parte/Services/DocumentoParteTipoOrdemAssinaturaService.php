<?php

namespace App\Domain\Documento\Parte\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Documento\Parte\Models\documento_parte_tipo_ordem_assinatura;

use App\Domain\Documento\Parte\Contracts\DocumentoParteTipoOrdemAssinaturaRepositoryInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteTipoOrdemAssinaturaServiceInterface;

class DocumentoParteTipoOrdemAssinaturaService implements DocumentoParteTipoOrdemAssinaturaServiceInterface
{
    /**
     * @var DocumentoParteTipoOrdemAssinaturaRepositoryInterface
     */
    protected $DocumentoParteTipoOrdemAssinaturaRepositoryInterface;

    /**
     * DocumentoParteTipoOrdemAssinaturaService constructor.
     * @param DocumentoParteTipoOrdemAssinaturaRepositoryInterface $DocumentoParteTipoOrdemAssinaturaRepositoryInterface
     */
    public function __construct(DocumentoParteTipoOrdemAssinaturaRepositoryInterface $DocumentoParteTipoOrdemAssinaturaRepositoryInterface)
    {
        $this->DocumentoParteTipoOrdemAssinaturaRepositoryInterface = $DocumentoParteTipoOrdemAssinaturaRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args)
    {
        return $this->DocumentoParteTipoOrdemAssinaturaRepositoryInterface->listar($args);
    }

    /**
     * @param int $id_documento_parte_tipo_ordem_assinatura
     * @return documento_parte_tipo_ordem_assinatura|null
     */
    public function buscar(int $id_documento_parte_tipo_ordem_assinatura): ?documento_parte_tipo_ordem_assinatura
    {
        return $this->DocumentoParteTipoOrdemAssinaturaRepositoryInterface->buscar($id_documento_parte_tipo_ordem_assinatura);
    }

    /**
     * @param stdClass $args
     * @return documento_parte_tipo_ordem_assinatura
     */
    public function inserir(stdClass $args): documento_parte_tipo_ordem_assinatura
    {
        return $this->DocumentoParteTipoOrdemAssinaturaRepositoryInterface->inserir($args);
    }
}
