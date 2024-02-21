<?php

namespace App\Domain\VScore\Contracts;

use stdClass;

use App\Domain\VScore\Models\vscore_transacao;

interface VScoreTransacaoServiceInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder;
    
    /**
     * @param int $id_vscore_transacao
     * @return vscore_transacao|null
     */
    public function buscar(int $id_vscore_transacao) : ?vscore_transacao;

    /**
     * @param string $uuid
     * @return vscore_transacao|null
     */
    public function buscar_uuid(string $uuid) : ?vscore_transacao;

    /**
     * @param stdClass $args
     * @return vscore_transacao
     */
    public function inserir(stdClass $args) : vscore_transacao;
    
    /**
     * @param vscore_transacao $vscore_transacao
     * @param stdClass $args
     * @return vscore_transacao
     */
    public function alterar(vscore_transacao $vscore_transacao, stdClass $args) : vscore_transacao;
}
