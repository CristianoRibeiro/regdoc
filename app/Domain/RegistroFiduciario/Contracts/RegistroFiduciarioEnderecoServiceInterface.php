<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_endereco;
use stdClass;

interface RegistroFiduciarioEnderecoServiceInterface
{
    /**
     * @param stdClass $params
     * @return registro_fiduciario_endereco
     */
    public function inserir(stdClass $args): registro_fiduciario_endereco;

    /**
     * @param registro_fiduciario_endereco $registro_fiduciario_endereco
     * @param stdClass $args
     * @return registro_fiduciario_endereco
     */
    public function alterar(registro_fiduciario_endereco $registro_fiduciario_endereco, stdClass $args): registro_fiduciario_endereco;

    /**
     * @param registro_fiduciario_endereco $registro_fiduciario_endereco
     * @return bool
     */
    public function deletar(registro_fiduciario_endereco $registro_fiduciario_endereco) : bool;
}
