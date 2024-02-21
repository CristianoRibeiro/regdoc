<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoImovelRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioVerificacaoImovelServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_verificacoes_imovel;

class RegistroFiduciarioVerificacaoImovelService implements RegistroFiduciarioVerificacaoImovelServiceInterface
{
    /**
     * @var RegistroFiduciarioVerificacaoImovelRepositoryInterface
     */
    protected $RegistroFiduciarioVerificacaoImovelRepositoryInterface;

    /**
     * RegistroFiduciarioVerificacaoImovelService constructor.
     * @param RegistroFiduciarioVerificacaoImovelRepositoryInterface $RegistroFiduciarioVerificacaoImovelRepositoryInterface
     */
    public function __construct(RegistroFiduciarioVerificacaoImovelRepositoryInterface $RegistroFiduciarioVerificacaoImovelRepositoryInterface)
    {
        $this->RegistroFiduciarioVerificacaoImovelRepositoryInterface = $RegistroFiduciarioVerificacaoImovelRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_verificacoes_imovel
     */
    public function inserir(stdClass $args): registro_fiduciario_verificacoes_imovel
    {
        return $this->RegistroFiduciarioVerificacaoImovelRepositoryInterface->inserir($args);
    }
}
