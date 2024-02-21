<?php

namespace App\Domain\Integracao\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Integracao\Contracts\IntegracaoRepositoryInterface;
use App\Domain\Integracao\Contracts\IntegracaoServiceInterface;

use App\Domain\Integracao\Models\integracao;

class IntegracaoService implements IntegracaoServiceInterface
{
    /**
     * @var IntegracaoRepositoryInterface
     */
    protected $IntegracaoRepositoryInterface;

    /**
     * IntegracaoService constructor.
     * @param IntegracaoRepositoryInterface $IntegracaoRepositoryInterface
     */
    public function __construct(IntegracaoRepositoryInterface $IntegracaoRepositoryInterface)
    {
        $this->IntegracaoRepositoryInterface = $IntegracaoRepositoryInterface;
    }

    /**
     * @param int $id_integracao
     * @return integracao|null
     */
    public function buscar(int $id_integracao) : ?integracao
    {
        return $this->IntegracaoRepositoryInterface->buscar($id_integracao);
    }

    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return $this->IntegracaoRepositoryInterface->listar();
    }
}
