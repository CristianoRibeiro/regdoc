<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_procurador;

interface RegistroFiduciarioProcuradorServiceInterface
{
    /**
     * @param int $id_registro_fiduciario_procurador
     * @return registro_fiduciario_procurador
     */
    public function buscar_procurador(int $id_registro_fiduciario_procurador): registro_fiduciario_procurador;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_procurador
     */
    public function inserir(stdClass $args) : registro_fiduciario_procurador;

    /**
     * @param registro_fiduciario_procurador $registro_fiduciario_procurador
     * @param stdClass $args
     * @return registro_fiduciario_procurador
     */
    public function alterar(registro_fiduciario_procurador $registro_fiduciario_procurador, stdClass $args) : registro_fiduciario_procurador;
}
