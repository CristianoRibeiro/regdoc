<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

interface RegistroFiduciarioServiceInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder;

    /**
     * @param int $id_registro_fiduciario
     * @return registro_fiduciario|null
     */
    public function buscar(int $id_registro_fiduciario): ?registro_fiduciario;

    /**
     * @param string $uuid
     * @return registro_fiduciario|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario;

    /**
     * @param stdClass $args
     * @return registro_fiduciario
     */
    public function inserir(stdClass $args): registro_fiduciario;

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @param stdClass $args
     * @return registro_fiduciario
     * @throws Exception
     */
    public function alterar(registro_fiduciario $registro_fiduciario, stdClass $args) : registro_fiduciario;

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @return void
     * @throws Exception
     */
    public function iniciar_proposta(registro_fiduciario $registro_fiduciario);

    /**
     * @param stdClass $args
     * @param registro_fiduciario $registro_fiduciario
     * @param bool $in_api
     * @return void
     * @throws Exception
     */
    public function transformar_contrato(stdClass $args, registro_fiduciario $registro_fiduciario, bool $in_api = false);

    /**
     * @param stdClass $args
     * @param registro_fiduciario $registro_fiduciario
     * @param bool $in_api
     * @return void
     * @throws Exception
     */
    public function iniciar_documentacao(registro_fiduciario $registro_fiduciario);

    /**
     * @param registro_fiduciario $registro_fiduciario, string $de_motivo_cancelamento, string $finalizar
     * @return void
     * @throws Exception
     */
    public function cancelar(registro_fiduciario $registro_fiduciario, string $de_motivo_cancelamento, string $finalizar);

    /**
     * @param registro_fiduciario $registro_fiduciario
     * @return void
     * @throws Exception
     */
    public function iniciar_emissoes(registro_fiduciario $registro_fiduciario);

    public function verifica_todas_partes_emitiram(registro_fiduciario $registro_fiduciario): bool;
}
