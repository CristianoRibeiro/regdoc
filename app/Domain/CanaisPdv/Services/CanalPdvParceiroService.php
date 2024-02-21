<?php

namespace App\Domain\CanaisPdv\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\CanaisPdv\Models\canal_pdv_parceiro;

use App\Domain\CanaisPdv\Contracts\CanalPdvParceiroRepositoryInterface;
use App\Domain\CanaisPdv\Contracts\CanalPdvParceiroServiceInterface;

class CanalPdvParceiroService implements CanalPdvParceiroServiceInterface
{
    /**
     * @var CanalPdvParceiroRepositoryInterface
     */
    protected $CanalPdvParceiroRepositoryInterface;

    /**
     * CanalPdvParceiroService constructor.
     * @param CanalPdvParceiroRepositoryInterface $CanalPdvParceiroRepositoryInterface
     */
    public function __construct(CanalPdvParceiroRepositoryInterface $CanalPdvParceiroRepositoryInterface)
    {
        $this->CanalPdvParceiroRepositoryInterface = $CanalPdvParceiroRepositoryInterface;
    }

    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder
    {
        return $this->CanalPdvParceiroRepositoryInterface->listar($filtros);
    }

    /**
     * @param int $id_canal_pdv_parceiro
     * @return canal_pdv_parceiro|null
     */
    public function buscar(int $id_canal_pdv_parceiro): ?canal_pdv_parceiro
    {
        return $this->CanalPdvParceiroRepositoryInterface->buscar($id_canal_pdv_parceiro);
    }

    public function buscarCnpj(string $cnpj) : ?canal_pdv_parceiro
    {
        return $this->CanalPdvParceiroRepositoryInterface->buscarCnpj($cnpj);
    }

    /**
     * @param stdClass $args
     * @return canal_pdv_parceiro
     */
    public function inserir(stdClass $args): canal_pdv_parceiro
    {
        return $this->CanalPdvParceiroRepositoryInterface->inserir($args);
    }

    /**
     * @param canal_pdv_parceiro $canal_pdv_parceiro
     * @param stdClass $args
     * @return canal_pdv_parceiro
     */
    public function alterar(canal_pdv_parceiro $canal_pdv_parceiro, stdClass $args) : canal_pdv_parceiro
    {
        return $this->CanalPdvParceiroRepositoryInterface->alterar($canal_pdv_parceiro, $args);
    }

    /**
     * @param canal_pdv_parceiro $canal_pdv_parceiro
     * @return canal_pdv_parceiro
     */
    public function desativar(canal_pdv_parceiro $canal_pdv_parceiro) : canal_pdv_parceiro
    {
        return $this->CanalPdvParceiroRepositoryInterface->desativar($canal_pdv_parceiro);
    }

    /**
     * @return Collection
     */
    public function listar_nome_pessoas_fisicas() : Collection
    {
        return $this->CanalPdvParceiroRepositoryInterface->listar_nome_pessoas_fisicas();
    }

    /**
     * @return Collection
     */
    public function listar_nome_pessoas_juridicas() : Collection
    {
        return $this->CanalPdvParceiroRepositoryInterface->listar_nome_pessoas_juridicas();
    }
}
