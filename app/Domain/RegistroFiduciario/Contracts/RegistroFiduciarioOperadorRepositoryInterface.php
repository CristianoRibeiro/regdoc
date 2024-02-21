<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_operador;

interface RegistroFiduciarioOperadorRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_operador
     * @return registro_fiduciario_operador|null
     */
    public function buscar(int $id_registro_fiduciario_operador) : ?registro_fiduciario_operador;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_operador
     */
    public function inserir(stdClass $args) : registro_fiduciario_operador;

    /**
     * @param registro_fiduciario_operador $registro_fiduciario_operador
     * @param stdClass $args
     * @return registro_fiduciario_operador
     */
    public function alterar(registro_fiduciario_operador $registro_fiduciario_operador, stdClass $args) : registro_fiduciario_operador;

    /**
     * @param registro_fiduciario_operador $registro_fiduciario_operador
     * @return registro_fiduciario_operador
     */
    public function deletar(registro_fiduciario_operador $registro_fiduciario_operador) : registro_fiduciario_operador;
}
