<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_reembolso;

interface RegistroFiduciarioReembolsoServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_reembolso
     * @return registro_fiduciario_reembolso|null
     */
    public function buscar(int $id_registro_fiduciario_reembolso) : ?registro_fiduciario_reembolso;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_reembolso
     */
    public function inserir(stdClass $args) : registro_fiduciario_reembolso;

    /**
     * @param registro_fiduciario_reembolso $registro_fiduciario_reembolso
     * @param stdClass $args
     * @return registro_fiduciario_reembolso
     */
    public function alterar(registro_fiduciario_reembolso $registro_fiduciario_reembolso, stdClass $args) : registro_fiduciario_reembolso;
}
