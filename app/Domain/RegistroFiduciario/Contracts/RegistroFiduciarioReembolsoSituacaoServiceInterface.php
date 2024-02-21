<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_reembolso_situacao;

interface RegistroFiduciarioReembolsoSituacaoServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_reembolso_situacao
     * @return registro_fiduciario_reembolso_situacao|null
     */
    public function buscar(int $id_registro_fiduciario_reembolso_situacao) : ?registro_fiduciario_reembolso_situacao;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_reembolso_situacao
     */
    public function inserir(stdClass $args) : registro_fiduciario_reembolso_situacao;

    /**
     * @param registro_fiduciario_reembolso_situacao $registro_fiduciario_reembolso_situacao
     * @param stdClass $args
     * @return registro_fiduciario_reembolso_situacao
     */
    public function alterar(registro_fiduciario_reembolso_situacao $registro_fiduciario_reembolso_situacao, stdClass $args) : registro_fiduciario_reembolso_situacao;
}
