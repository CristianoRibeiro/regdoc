<?php

namespace App\Domain\Estado\Services;

use App\Domain\Estado\Contracts\CidadeRepositoryInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Estado\Models\cidade;

class CidadeService implements CidadeServiceInterface
{
    /**
     * @var CidadeRepositoryInterface
     */
    protected $CidadeRepositoryInterface;

    /**
     * CidadeService constructor.
     * @param CidadeRepositoryInterface $CidadeRepositoryInterface
     */
    public function __construct(CidadeRepositoryInterface $CidadeRepositoryInterface)
    {
        $this->CidadeRepositoryInterface = $CidadeRepositoryInterface;
    }

    /**
     * @param int $id_cidade
     * @return cidade
     */
    public function buscar_cidade(int $id_cidade) : cidade
    {
        return $this->CidadeRepositoryInterface->buscar_cidade($id_cidade);
    }

    /**
     * @param int $id_estado
     * @return Collection
     */
    public function cidades_disponiveis(int $id_estado) : ?Collection
    {
        return $this->CidadeRepositoryInterface->cidades_disponiveis($id_estado);
    }

    /**
     * @param int $co_ibge
     * @return cidade
     */
    public function buscar_ibge(string $co_ibge) : cidade
    {
        return $this->CidadeRepositoryInterface->buscar_ibge($co_ibge);
    }
}
