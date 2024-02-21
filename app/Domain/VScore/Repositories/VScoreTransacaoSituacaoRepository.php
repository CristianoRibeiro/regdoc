<?php

namespace App\Domain\VScore\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\VScore\Models\vscore_transacao_situacao;

use App\Domain\VScore\Contracts\VScoreTransacaoSituacaoRepositoryInterface;

class VScoreTransacaoSituacaoRepository implements VScoreTransacaoSituacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return vscore_transacao_situacao::where('in_registro_ativo', 'S')
            ->orderBy('dt_cadastro', 'DESC')
            ->get();
    }
}
