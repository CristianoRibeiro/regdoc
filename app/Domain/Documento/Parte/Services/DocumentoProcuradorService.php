<?php

namespace App\Domain\Documento\Parte\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Parte\Models\documento_procurador;

use App\Domain\Documento\Parte\Contracts\DocumentoProcuradorRepositoryInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoProcuradorServiceInterface;

class DocumentoProcuradorService implements DocumentoProcuradorServiceInterface
{
    /**
     * @var DocumentoProcuradorRepositoryInterface;
     */
    protected $DocumentoProcuradorRepositoryInterface;

    /**
     * DocumentoProcuradorService constructor.
     * @param DocumentoProcuradorRepositoryInterface $DocumentoProcuradorRepositoryInterface
     */
    public function __construct(DocumentoProcuradorRepositoryInterface $DocumentoProcuradorRepositoryInterface)
    {
        $this->DocumentoProcuradorRepositoryInterface = $DocumentoProcuradorRepositoryInterface;
    }

     /**
     * @param int $id_documento_procurador
     * @return documento_procurador|null
     */
    public function buscar(int $id_documento_procurador): ?documento_procurador
    {
        return $this->DocumentoProcuradorRepositoryInterface->buscar($id_documento_procurador);
    }

    /**
     * @param stdClass $args
     * @return documento_procurador
    */
    public function inserir(stdClass $args): documento_procurador
    {
        return $this->DocumentoProcuradorRepositoryInterface->inserir($args);
    }

    /**
     * @param documento_procurador $documento_procurador
     * @param stdClass $args
     * @return documento_procurador
     */
    public function alterar(documento_procurador $documento_procurador, stdClass $args): documento_procurador
    {
        return $this->DocumentoProcuradorRepositoryInterface->alterar($documento_procurador, $args);
    }

    /**
     * @param stdClass $args
     * @return documento_procurador
     */
    public function buscar_alterar(stdClass $args): documento_procurador
    {
        return $this->DocumentoProcuradorRepositoryInterface->buscar_alterar($args);
    }
}
