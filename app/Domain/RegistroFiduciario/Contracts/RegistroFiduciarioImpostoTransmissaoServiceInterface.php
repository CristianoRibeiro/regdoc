<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_impostotransmissao;
use stdClass;

interface RegistroFiduciarioImpostoTransmissaoServiceInterface
{
    /**
     * @param stdClass $params
     * @return registro_fiduciario_impostotransmissao
     */
    public function inserir(stdClass $params): registro_fiduciario_impostotransmissao;

    /**
     * @param int $id_registro_fiduciario_impostotransmissao
     * @param stdClass $args
     * @return registro_fiduciario_impostotransmissao
     */
    public function alterar(int $id_registro_fiduciario_impostotransmissao, stdClass $args): registro_fiduciario_impostotransmissao;
}
