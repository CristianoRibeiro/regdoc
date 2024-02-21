<?php

namespace App\Domain\VScore\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\VScore\Contracts\VScoreTransacaoSituacaoServiceInterface;
use App\Domain\VScore\Contracts\VScoreTransacaoSituacaoRepositoryInterface;

class VScoreTransacaoSituacaoService implements VScoreTransacaoSituacaoServiceInterface
{
    /**
     * @var VScoreTransacaoSituacaoRepositoryInterface
     */
    protected $VScoreTransacaoSituacaoRepositoryInterface;

    /**
     * VScoreTransacaoService constructor.
     * @param VScoreTransacaoSituacaoRepositoryInterface $VScoreTransacaoSituacaoRepositoryInterface
     */
    public function __construct(VScoreTransacaoSituacaoRepositoryInterface $VScoreTransacaoSituacaoRepositoryInterface)
    {
        $this->VScoreTransacaoSituacaoRepositoryInterface = $VScoreTransacaoSituacaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return $this->VScoreTransacaoSituacaoRepositoryInterface->listar();
    }
}
