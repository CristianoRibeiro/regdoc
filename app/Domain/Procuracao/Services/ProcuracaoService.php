<?php

namespace App\Domain\Procuracao\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Procuracao\Models\procuracao;

use App\Domain\Procuracao\Contracts\ProcuracaoRepositoryInterface;
use App\Domain\Procuracao\Contracts\ProcuracaoServiceInterface;

class ProcuracaoService implements ProcuracaoServiceInterface
{
    /**
     * @var ProcuracaoRepositoryInterface
     */
    protected $ProcuracaoRepositoryInterface;

    /**
     * ProcuracaoService constructor.
     * @param ProcuracaoRepositoryInterface $ProcuracaoRepositoryInterface
     */
    public function __construct(ProcuracaoRepositoryInterface $ProcuracaoRepositoryInterface)
    {
        $this->ProcuracaoRepositoryInterface = $ProcuracaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->ProcuracaoRepositoryInterface->listar();
    }

    /**
     * @param string $uuid
     * @return procuracao|null
     */
    public function buscar_uuid(string $uuid) : ?procuracao
    {
        return $this->ProcuracaoRepositoryInterface->buscar_uuid($uuid);
    }
}
