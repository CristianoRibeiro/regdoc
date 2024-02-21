<?php

namespace App\Domain\Construtora\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Construtora\Contracts\ConstrutoraRepositoryInterface;
use App\Domain\Construtora\Contracts\ConstrutoraServiceInterface;

use App\Domain\Construtora\Models\construtora;

class ConstrutoraService implements ConstrutoraServiceInterface
{
    /**
     * @var ConstrutoraRepositoryInterface
     */
    protected $ConstrutoraRepositoryInterface;

    /**
     * ConstrutoraService constructor.
     * @param ConstrutoraRepositoryInterface $ConstrutoraRepositoryInterface
     */
    public function __construct(ConstrutoraRepositoryInterface $ConstrutoraRepositoryInterface)
    {
        $this->ConstrutoraRepositoryInterface = $ConstrutoraRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function lista_construtoras() : Collection
    {
        return $this->ConstrutoraRepositoryInterface->lista_construtoras();
    }

    /**
     * @return Collection
     */
    public function lista_construtora_pessoa(int $id_pessoa) : Collection
    {
        return $this->ConstrutoraRepositoryInterface->lista_construtora_pessoa($id_pessoa);
    }

    /**
     * @param int $id
     * @return construtora
     */
    public function busca_construtora(int $id_construtora) : construtora
    {
        return $this->ConstrutoraRepositoryInterface->busca_construtora($id_construtora);
    }
}
