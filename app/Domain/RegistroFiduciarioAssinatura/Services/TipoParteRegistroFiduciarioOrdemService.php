<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Services;

use stdClass;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\TipoParteRegistroFiduciarioOrdemRepositoryInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\TipoParteRegistroFiduciarioOrdemServiceInterface;

use App\Domain\RegistroFiduciario\Models\tipo_parte_registro_fiduciario_ordem;

class TipoParteRegistroFiduciarioOrdemService implements TipoParteRegistroFiduciarioOrdemServiceInterface
{
    /**
     * @var TipoParteRegistroFiduciarioOrdemRepositoryInterface
     */
    protected $TipoParteRegistroFiduciarioOrdemRepositoryInterface;

    /**
     * TipoParteRegistroFiduciarioOrdemService constructor.
     * @param TipoParteRegistroFiduciarioOrdemRepositoryInterface $TipoParteRegistroFiduciarioOrdemRepositoryInterface
     */
    public function __construct(TipoParteRegistroFiduciarioOrdemRepositoryInterface $TipoParteRegistroFiduciarioOrdemRepositoryInterface)
    {
        $this->TipoParteRegistroFiduciarioOrdemRepositoryInterface = $TipoParteRegistroFiduciarioOrdemRepositoryInterface;
    }

     /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args)
    {
        return $this->TipoParteRegistroFiduciarioOrdemRepositoryInterface->listar($args);
    }
}
