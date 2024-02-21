<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_operacao;
use stdClass;

interface RegistroFiduciarioOperacaoServiceInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_operacao
     */
    public function inserir(stdClass $args): registro_fiduciario_operacao;

    /**
     * @param registro_fiduciario_operacao $registro_fiduciario_operacao
     * @param stdClass $args
     * @return registro_fiduciario_operacao
     */
    public function alterar(registro_fiduciario_operacao $registro_fiduciario_operacao, stdClass $args) : registro_fiduciario_operacao;
}
