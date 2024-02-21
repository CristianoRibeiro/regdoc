<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_custodiante;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

interface RegistroFiduciarioCustodianteRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_custodiante
     * @return registro_fiduciario_custodiante|null
     */
    public function buscar(int $id_registro_fiduciario_custodiante) : ?registro_fiduciario_custodiante;

    /**
     * @param int $id_cidade
     * @return Collection
     */
    public function custodiantes_disponiveis(int $id_cidade) : Collection;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_custodiante
     */
    public function insere(stdClass $args) : registro_fiduciario_custodiante;
}
