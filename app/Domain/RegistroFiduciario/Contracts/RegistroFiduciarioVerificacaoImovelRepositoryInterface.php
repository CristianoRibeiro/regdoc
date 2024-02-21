<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_verificacoes_imovel;
use stdClass;

interface RegistroFiduciarioVerificacaoImovelRepositoryInterface
{
    /**
     * @param stdClass $params
     * @return registro_fiduciario_verificacoes_imovel
     */
    public function inserir(stdClass $params): registro_fiduciario_verificacoes_imovel;

}
