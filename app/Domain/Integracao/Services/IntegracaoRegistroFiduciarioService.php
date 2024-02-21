<?php

namespace App\Domain\Integracao\Services;

use stdClass;

use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioRepositoryInterface;
use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioServiceInterface;

class IntegracaoRegistroFiduciarioService implements IntegracaoRegistroFiduciarioServiceInterface
{
    /**
     * @var IntegracaoRegistroFiduciarioRepositoryInterface
     */
    protected $IntegracaoRegistroFiduciarioRepositoryInterface;

    /**
     * IntegracaoRegistroFiduciarioService constructor.
     * @param IntegracaoRegistroFiduciarioRepositoryInterface $IntegracaoRegistroFiduciarioRepositoryInterface
     */
    public function __construct(IntegracaoRegistroFiduciarioRepositoryInterface $IntegracaoRegistroFiduciarioRepositoryInterface)
    {
        $this->IntegracaoRegistroFiduciarioRepositoryInterface = $IntegracaoRegistroFiduciarioRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return int
     */
    public function definir_integracao(stdClass $args) : int
    {
        return $this->IntegracaoRegistroFiduciarioRepositoryInterface->definir_integracao($args);
    }
}
