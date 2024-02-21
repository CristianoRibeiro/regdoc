<?php

namespace App\Domain\Documento\Parte\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Parte\Models\documento_parte;

use App\Domain\Documento\Parte\Contracts\DocumentoParteRepositoryInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteServiceInterface;

class DocumentoParteService implements DocumentoParteServiceInterface
{
    /**
     * @var DocumentoParteRepositoryInterface;
     */
    protected $DocumentoParteRepositoryInterface;

    /**
     * DocumentoParteService constructor.
     * @param DocumentoParteRepositoryInterface $DocumentoParteRepositoryInterface
     */
    public function __construct(DocumentoParteRepositoryInterface $DocumentoParteRepositoryInterface)
    {
        $this->DocumentoParteRepositoryInterface = $DocumentoParteRepositoryInterface;
    }

    /**
     * @param int $id_documento_parte
     * @return documento_parte|null
     */
    public function buscar(int $id_documento_parte): ?documento_parte
    {
        return $this->DocumentoParteRepositoryInterface->buscar($id_documento_parte);
    }

    /**
     * @param string $uuid
     * @return documento_parte|null
     */
    public function buscar_uuid(string $uuid) : ?documento_parte
    {
        return $this->DocumentoParteRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return documento_parte
    */
    public function inserir(stdClass $args): documento_parte
    {
        return $this->DocumentoParteRepositoryInterface->inserir($args);
    }

    /**
     * @param documento_parte $documento_parte
     * @param stdClass $args
     * @return documento_parte
     */
    public function alterar(documento_parte $documento_parte, stdClass $args): documento_parte
    {
        return $this->DocumentoParteRepositoryInterface->alterar($documento_parte, $args);
    }

    /**
     * @param stdClass $args
     * @return documento_parte
     */
    public function buscar_alterar(stdClass $args): documento_parte
    {
        return $this->DocumentoParteRepositoryInterface->buscar_alterar($args);
    }
}
