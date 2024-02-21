<?php

namespace App\Domain\Configuracao\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Configuracao\Contracts\ConfiguracaoRepositoryInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoServiceInterface;

class ConfiguracaoService implements ConfiguracaoServiceInterface
{
    /**
     * @var ConfiguracaoRepositoryInterface
     */
    protected $ConfiguracaoRepositoryInterface;

    /**
     * ConfiguracaoService constructor.
     * @param ConfiguracaoRepositoryInterface $ConfiguracaoRepositoryInterface
     */
    public function __construct(ConfiguracaoRepositoryInterface $ConfiguracaoRepositoryInterface)
    {
        $this->ConfiguracaoRepositoryInterface = $ConfiguracaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return $this->ConfiguracaoRepositoryInterface->listar();
    }
}
