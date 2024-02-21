<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoParteRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoParteServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_verificacoes_parte;

class RegistroFiduciarioVerificacaoParteService implements RegistroFiduciarioVerificacaoParteServiceInterface
{
    /**
     * @var RegistroFiduciarioVerificacaoParteRepositoryInterface
     */
    protected $RegistroFiduciarioVerificacaoParteRepositoryInterface;

    /**
     * RegistroFiduciarioVerificacaoParteService constructor.
     * @param RegistroFiduciarioVerificacaoParteRepositoryInterface $RegistroFiduciarioVerificacaoParteRepositoryInterface
     */
    public function __construct(RegistroFiduciarioVerificacaoParteRepositoryInterface $RegistroFiduciarioVerificacaoParteRepositoryInterface)
    {
        $this->RegistroFiduciarioVerificacaoParteRepositoryInterface = $RegistroFiduciarioVerificacaoParteRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_verificacoes_parte
     */
    public function inserir(stdClass $args): registro_fiduciario_verificacoes_parte
    {
        return $this->RegistroFiduciarioVerificacaoParteRepositoryInterface->inserir($args);
    }
}
