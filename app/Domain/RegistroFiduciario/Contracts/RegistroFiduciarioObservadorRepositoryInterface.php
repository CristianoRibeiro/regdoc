<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_observador;

interface RegistroFiduciarioObservadorRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_observador
     * @return registro_fiduciario_observador|null
     */
    public function buscar(int $id_registro_fiduciario_observador) : ?registro_fiduciario_observador;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_observador
     */
    public function inserir(stdClass $args) : registro_fiduciario_observador;

    /**
     * @param registro_fiduciario_observador $registro_fiduciario_observador
     * @param stdClass $args
     * @return registro_fiduciario_observador
     */
    public function alterar(registro_fiduciario_observador $registro_fiduciario_observador, stdClass $args) : registro_fiduciario_observador;

    /**
     * @param registro_fiduciario_observador $registro_fiduciario_observador
     * @return bool
     */
    public function deletar(registro_fiduciario_observador $registro_fiduciario_observador) : bool;
}
