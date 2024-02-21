<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImpostoTransmissaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImpostoTransmissaoServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_impostotransmissao;

class RegistroFiduciarioImpostoTransmissaoService implements RegistroFiduciarioImpostoTransmissaoServiceInterface
{
    /**
     * @var RegistroFiduciarioImpostoTransmissaoRepositoryInterface
     */
    protected $RegistroFiduciarioImpostoTransmissaoRepositoryInterface;

    /**
     * RegistroFiduciarioImpostoTransmissaoService constructor.
     * @param RegistroFiduciarioImpostoTransmissaoRepositoryInterface $RegistroFiduciarioImpostoTransmissaoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioImpostoTransmissaoRepositoryInterface $RegistroFiduciarioImpostoTransmissaoRepositoryInterface)
    {
        $this->RegistroFiduciarioImpostoTransmissaoRepositoryInterface = $RegistroFiduciarioImpostoTransmissaoRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_impostotransmissao
     */
    public function inserir(stdClass $args): registro_fiduciario_impostotransmissao
    {
        return $this->RegistroFiduciarioImpostoTransmissaoRepositoryInterface->inserir($args);
    }

    /**
     * @param int $id_registro_fiduciario_impostotransmissao
     * @param stdClass $args
     * @return registro_fiduciario_impostotransmissao
     */
    public function alterar(int $id_registro_fiduciario_impostotransmissao, stdClass $args): registro_fiduciario_impostotransmissao
    {
        return $this->RegistroFiduciarioImpostoTransmissaoRepositoryInterface->alterar($id_registro_fiduciario_impostotransmissao, $args);
    }
}
