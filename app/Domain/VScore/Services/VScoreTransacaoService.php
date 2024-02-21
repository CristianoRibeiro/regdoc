<?php

namespace App\Domain\VScore\Services;

use stdClass;

use App\Domain\VScore\Contracts\VScoreTransacaoServiceInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoRepositoryInterface;

use App\Domain\VScore\Models\vscore_transacao;

class VScoreTransacaoService implements VScoreTransacaoServiceInterface
{
    /**
     * @var VScoreTransacaoRepositoryInterface
     */
    protected $VScoreTransacaoRepositoryInterface;

    /**
     * VScoreTransacaoService constructor.
     * @param VScoreTransacaoRepositoryInterface $VScoreTransacaoRepositoryInterface
     */
    public function __construct(VScoreTransacaoRepositoryInterface $VScoreTransacaoRepositoryInterface)
    {
        $this->VScoreTransacaoRepositoryInterface = $VScoreTransacaoRepositoryInterface;
    }

    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder
    {
        return $this->VScoreTransacaoRepositoryInterface->listar($filtros);
    }

    /**
     * @param int $id_vscore_transacao
     * @return vscore_transacao|null
     */
    public function buscar(int $id_vscore_transacao) : ?vscore_transacao
    {
        return $this->VScoreTransacaoRepositoryInterface->buscar($id_vscore_transacao);
    }

    /**
     * @param string $uuid
     * @return vscore_transacao|null
     */
    public function buscar_uuid(string $uuid) : ?vscore_transacao
    {
        return $this->VScoreTransacaoRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return vscore_transacao
     * @throws Exception
     */
    public function inserir(stdClass $args) : vscore_transacao
    {
        return $this->VScoreTransacaoRepositoryInterface->inserir($args);
    }
    
    /**
     * @param vscore_transacao $vscore_transacao
     * @param stdClass $args
     * @return vscore_transacao
     */
    public function alterar(vscore_transacao $vscore_transacao, stdClass $args): vscore_transacao
    {
        return $this->VScoreTransacaoRepositoryInterface->alterar($vscore_transacao, $args);
    }
}
