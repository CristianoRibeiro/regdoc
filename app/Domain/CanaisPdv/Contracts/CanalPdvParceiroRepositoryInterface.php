<?php

namespace App\Domain\CanaisPdv\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\CanaisPdv\Models\canal_pdv_parceiro;

interface CanalPdvParceiroRepositoryInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Database\Eloquent\Builder;

    /**
     * @param int $id_canal_pdv_parceiro
     * @return canal_pdv_parceiro|null
     */
    public function buscar(int $id_canal_pdv_parceiro) : ?canal_pdv_parceiro;

    public function buscarCnpj(string $cnpj) : ?canal_pdv_parceiro;

    /**
     * @param stdClass $args
     * @return canal_pdv_parceiro
     */
    public function inserir(stdClass $args) : canal_pdv_parceiro;

    /**
     * @param canal_pdv_parceiro $canal_pdv_parceiro
     * @param stdClass $args
     * @return canal_pdv_parceiro
     */
    public function alterar(canal_pdv_parceiro $canal_pdv_parceiro, stdClass $args) : canal_pdv_parceiro;

    /**
     * @param canal_pdv_parceiro $canal_pdv_parceiro
     * @return canal_pdv_parceiro
     */
    public function desativar(canal_pdv_parceiro $canal_pdv_parceiro) : canal_pdv_parceiro;

    
    /**
     * @return Collection
     */
    public function listar_nome_pessoas_fisicas() : Collection;

    
    /**
     * @return Collection
     */
    public function listar_nome_pessoas_juridicas() : Collection;
    

}
