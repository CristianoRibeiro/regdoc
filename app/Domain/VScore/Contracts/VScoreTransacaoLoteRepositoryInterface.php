<?php

namespace App\Domain\VScore\Contracts;

use stdClass;

use App\Domain\VScore\Models\vscore_transacao_lote;

interface VScoreTransacaoLoteRepositoryInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder;

    /**
     * @param int $id_vscore_transacao_lote
     * @return vscore_transacao_lote|null
     */
    public function buscar(int $id_vscore_transacao_lote) : ?vscore_transacao_lote;

    /**
     * @param string $uuid
     * @return vscore_transacao_lote|null
     */
    public function buscar_uuid(string $uuid) : ?vscore_transacao_lote;

    /**
     * @param stdClass $args
     * @return vscore_transacao_lote
     */
    public function inserir(stdClass $args) : vscore_transacao_lote;

    /**
     * @param vscore_transacao_lote $vscore_transacao_lote
     * @param stdClass $args
     * @return vscore_transacao_lote
     */
    public function alterar(vscore_transacao_lote $vscore_transacao_lote, stdClass $args) : vscore_transacao_lote;
}
