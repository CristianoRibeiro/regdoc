<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCredorRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCredorServiceInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_credor;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

class RegistroFiduciarioCredorService implements RegistroFiduciarioCredorServiceInterface
{
    /**
     * @var RegistroFiduciarioCredorRepositoryInterface
     */
    protected $RegistroFiduciarioCredorRepositoryInterface;

    public function __construct(RegistroFiduciarioCredorRepositoryInterface $RegistroFiduciarioCredorRepositoryInterface)
    {
        $this->RegistroFiduciarioCredorRepositoryInterface = $RegistroFiduciarioCredorRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_credor
     * @return registro_fiduciario_credor|null
     */
    public function buscar(int $id_registro_fiduciario_credor) : ?registro_fiduciario_credor
    {
        return $this->RegistroFiduciarioCredorRepositoryInterface->buscar($id_registro_fiduciario_credor);
    }

    /**
     * @param string $nu_cpf_cnpj
     * @return registro_fiduciario_credor|null
     */
    public function buscar_cnpj(string $nu_cpf_cnpj) : ?registro_fiduciario_credor
    {
        return $this->RegistroFiduciarioCredorRepositoryInterface->buscar_cnpj($nu_cpf_cnpj);
    }

    /**
     * @param int $id_cidade
     * @return Collection
     */
    public function credores_disponiveis(int $id_cidade, int $id_pessoa = NULL): Collection
    {
        return $this->RegistroFiduciarioCredorRepositoryInterface->credores_disponiveis($id_cidade, $id_pessoa);
    }

    /**
     * @param int $id_cidade
     * @param int $id_pessoa
     * @return Collection
     */
    public function credores_disponiveis_agencia(int $id_cidade, int $id_pessoa = 0) : Collection
    {
        return $this->RegistroFiduciarioCredorRepositoryInterface->credores_disponiveis_agencia($id_cidade, $id_pessoa);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_credor
     */
    public function insere(stdClass $args): registro_fiduciario_credor
    {
        return $this->RegistroFiduciarioCredorRepositoryInterface->insere($args);
    }
}
