<?php

namespace App\Domain\VScore\Services;

use stdClass;

use App\Domain\VScore\Contracts\VScoreTransacaoLoteServiceInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoLoteRepositoryInterface;

use App\Domain\VScore\Models\vscore_transacao_lote;

class VScoreTransacaoLoteService implements VScoreTransacaoLoteServiceInterface
{
    /**
     * @var VScoreTransacaoLoteRepositoryInterface
     */
    protected $VScoreTransacaoLoteRepositoryInterface;

    /**
     * VScoreTransacaoLoteService constructor.
     * @param VScoreTransacaoLoteRepositoryInterface $VScoreTransacaoLoteRepositoryInterface
     */
    public function __construct(VScoreTransacaoLoteRepositoryInterface $VScoreTransacaoLoteRepositoryInterface)
    {
        $this->VScoreTransacaoLoteRepositoryInterface = $VScoreTransacaoLoteRepositoryInterface;
    }

    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder
    {
        return $this->VScoreTransacaoLoteRepositoryInterface->listar($filtros);
    }

    /**
     * @param int $id_vscore_transacao_lote
     * @return vscore_transacao_lote|null
     */
    public function buscar(int $id_vscore_transacao_lote) : ?vscore_transacao_lote
    {
        return $this->VScoreTransacaoLoteRepositoryInterface->buscar($id_vscore_transacao_lote);
    }

    /**
     * @param string $uuid
     * @return vscore_transacao_lote|null
     */
    public function buscar_uuid(string $uuid) : ?vscore_transacao_lote
    {
        return $this->VScoreTransacaoLoteRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return vscore_transacao_lote
     * @throws Exception
     */
    public function inserir(stdClass $args) : vscore_transacao_lote
    {
        return $this->VScoreTransacaoLoteRepositoryInterface->inserir($args);
    }

    /**
     * @param vscore_transacao_lote $vscore_transacao_lote
     * @param stdClass $args
     * @return vscore_transacao_lote
     */
    public function alterar(vscore_transacao_lote $vscore_transacao_lote, stdClass $args): vscore_transacao_lote
    {
        return $this->VScoreTransacaoLoteRepositoryInterface->alterar($vscore_transacao_lote, $args);
    }
}
