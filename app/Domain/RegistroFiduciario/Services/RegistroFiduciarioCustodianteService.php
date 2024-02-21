<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCustodianteRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCustodianteServiceInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_custodiante;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

class RegistroFiduciarioCustodianteService implements RegistroFiduciarioCustodianteServiceInterface
{
    /**
     * @var RegistroFiduciarioCustodianteRepositoryInterface
     */
    protected $RegistroFiduciarioCustodianteRepositoryInterface;

    public function __construct(RegistroFiduciarioCustodianteRepositoryInterface $RegistroFiduciarioCustodianteRepositoryInterface)
    {
        $this->RegistroFiduciarioCustodianteRepositoryInterface = $RegistroFiduciarioCustodianteRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_custodiante
     * @return registro_fiduciario_custodiante|null
     */
    public function buscar(int $id_registro_fiduciario_custodiante) : ?registro_fiduciario_custodiante
    {
        return $this->RegistroFiduciarioCustodianteRepositoryInterface->buscar($id_registro_fiduciario_Custodiante);
    }

    /**
     * @param int $id_cidade
     * @return Collection
     */
    public function custodiantes_disponiveis(int $id_cidade): Collection
    {
        return $this->RegistroFiduciarioCustodianteRepositoryInterface->custodiantes_disponiveis($id_cidade);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_custodiante
     */
    public function insere(stdClass $args): registro_fiduciario_custodiante
    {
        return $this->RegistroFiduciarioCustodianteRepositoryInterface->insere($args);
    }
}
