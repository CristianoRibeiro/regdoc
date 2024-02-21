<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_operacao;

class RegistroFiduciarioOperacaoService implements RegistroFiduciarioOperacaoServiceInterface
{
    /**
     * @var RegistroFiduciarioOperacaoRepositoryInterface
     */
    protected $RegistroFiduciarioOperacaoRepositoryInterface;

    /**
     * RegistroFiduciarioOperacaoService constructor.
     * @param RegistroFiduciarioOperacaoRepositoryInterface $RegistroFiduciarioOperacaoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioOperacaoRepositoryInterface $RegistroFiduciarioOperacaoRepositoryInterface)
    {
        $this->RegistroFiduciarioOperacaoRepositoryInterface = $RegistroFiduciarioOperacaoRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_operacao
     */
    public function inserir(stdClass $args): registro_fiduciario_operacao
    {
        return $this->RegistroFiduciarioOperacaoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_operacao $registro_fiduciario_operacao
     * @param stdClass $args
     * @return registro_fiduciario_operacao
     */
    public function alterar(registro_fiduciario_operacao $registro_fiduciario_operacao, stdClass $args) : registro_fiduciario_operacao
    {
        return $this->RegistroFiduciarioOperacaoRepositoryInterface->alterar($registro_fiduciario_operacao, $args);
    }
}
