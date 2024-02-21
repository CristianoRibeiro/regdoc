<?php

namespace App\Domain\CanaisPdv\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;

use App\Domain\CanaisPdv\Models\canal_pdv_parceiro;

use App\Domain\CanaisPdv\Contracts\CanalPdvParceiroRepositoryInterface;

class CanalPdvParceiroRepository implements CanalPdvParceiroRepositoryInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function listar(stdClass $filtros):  \Illuminate\Database\Eloquent\Builder
    {
        $canal_pdv_parceiro = new canal_pdv_parceiro();
        $canal_pdv_parceiro = $canal_pdv_parceiro->where('in_canal_pdv_parceiro_ativo', 'S');

        if (isset($filtros->nome_canal_pdv_parceiro)) {
            $canal_pdv_parceiro = $canal_pdv_parceiro->where('nome_canal_pdv_parceiro', $filtros->nome_canal_pdv_parceiro);
        }
        
        if (isset($filtros->email_canal_pdv_parceiro)) {
            $canal_pdv_parceiro = $canal_pdv_parceiro->where('email_canal_pdv_parceiro', $filtros->email_canal_pdv_parceiro);
        }

        if (isset($filtros->codigo_canal_pdv_parceiro)) {
            $canal_pdv_parceiro = $canal_pdv_parceiro->where('codigo_canal_pdv_parceiro', $filtros->codigo_canal_pdv_parceiro);
        }

        if (isset($filtros->parceiro_canal_pdv_parceiro)) {
            $canal_pdv_parceiro = $canal_pdv_parceiro->where('parceiro_canal_pdv_parceiro', $filtros->parceiro_canal_pdv_parceiro);
        }

        if (($filtros->cnpj_canal_pdv_parceiro ?? 0) > 0) {
            $canal_pdv_parceiro = $canal_pdv_parceiro->where('cnpj_canal_pdv_parceiro', $filtros->cnpj_canal_pdv_parceiro);
        }

        $canal_pdv_parceiro = $canal_pdv_parceiro->orderBy('dt_cadastro','desc');
                       
        return $canal_pdv_parceiro;
    }

    /**
     * @param int $id_canal_pdv_parceiro
     * @return canal_pdv_parceiro|null
     */
    public function buscar(int $id_canal_pdv_parceiro) : ?canal_pdv_parceiro
    {
        return canal_pdv_parceiro::find($id_canal_pdv_parceiro);
    }

    public function buscarCnpj(string $cnpj) : ?canal_pdv_parceiro
    {
        return canal_pdv_parceiro::where('cnpj_canal_pdv_parceiro', $cnpj)->first();
    }

    /**
     * @param canal_pdv_parceiro $canal_pdv_parceiro
     * @param stdClass $args
     * @return canal_pdv_parceiro
     * @throws Exception
     */
    public function inserir(stdClass $args) : canal_pdv_parceiro
    {
        $novo_canal_pdv_parceiro = new canal_pdv_parceiro();
        $novo_canal_pdv_parceiro->nome_canal_pdv_parceiro = $args->nome_canal_pdv_parceiro;
        $novo_canal_pdv_parceiro->email_canal_pdv_parceiro = $args->email_canal_pdv_parceiro;
        $novo_canal_pdv_parceiro->codigo_canal_pdv_parceiro  = $args->codigo_canal_pdv_parceiro;
        $novo_canal_pdv_parceiro->parceiro_canal_pdv_parceiro  = $args->parceiro_canal_pdv_parceiro;
        $novo_canal_pdv_parceiro->cnpj_canal_pdv_parceiro  = $args->cnpj_canal_pdv_parceiro;
        $novo_canal_pdv_parceiro->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        if (!$novo_canal_pdv_parceiro->save()) {
            throw new Exception('Erro ao salvar o canal pdv parceiro.');
        }
        
        return $novo_canal_pdv_parceiro;
    }

    /**
     * @param canal_pdv_parceiro $canal_pdv_parceiro
     * @param stdClass $args
     * @return canal_pdv_parceiro
     * @throws Exception
     */
    public function alterar(canal_pdv_parceiro $canal_pdv_parceiro, stdClass $args) : canal_pdv_parceiro
    {
        if (isset($args->nome_canal_pdv_parceiro)) {
            $canal_pdv_parceiro->nome_canal_pdv_parceiro = $args->nome_canal_pdv_parceiro;
        }

        if (isset($args->email_canal_pdv_parceiro)) {
            $canal_pdv_parceiro->email_canal_pdv_parceiro = $args->email_canal_pdv_parceiro;
        }

        if (isset($args->codigo_canal_pdv_parceiro)) {
            $canal_pdv_parceiro->codigo_canal_pdv_parceiro = $args->codigo_canal_pdv_parceiro;
        }

        if (isset($args->parceiro_canal_pdv_parceiro)) {
            $canal_pdv_parceiro->parceiro_canal_pdv_parceiro = $args->parceiro_canal_pdv_parceiro;
        }

        if (($args->cnpj_canal_pdv_parceiro ?? 0) > 0) {
            $canal_pdv_parceiro->cnpj_canal_pdv_parceiro = $args->cnpj_canal_pdv_parceiro;
        }

        if (!$canal_pdv_parceiro->save()) {
            throw new  Exception('Erro ao atualizar o canal pdv parceiro.');
        }

        $canal_pdv_parceiro->refresh();

        return $canal_pdv_parceiro;
    }

    /**
     * @param canal_pdv_parceiro $canal_pdv_parceiro
     * @return canal_pdv_parceiro
     * @throws Exception
     */
    public function desativar(canal_pdv_parceiro $canal_pdv_parceiro) : canal_pdv_parceiro
    {
        $canal_pdv_parceiro->in_canal_pdv_parceiro_ativo = 'N';
      
        if (!$canal_pdv_parceiro->save()) {
            throw new  Exception('Erro ao desativar o canal pdv parceiro.');
        }

        $canal_pdv_parceiro->refresh();

        return $canal_pdv_parceiro;
    }

    /**
     * @return Collection
     */
    public function listar_nome_pessoas_fisicas() : Collection
    {
        $canal_pdv_parceiro = new canal_pdv_parceiro();
        $canal_pdv_parceiro = $canal_pdv_parceiro->where('in_canal_pdv_parceiro_ativo', 'S')->orderBy('nome_canal_pdv_parceiro', 'ASC');

        return $canal_pdv_parceiro->get();
    }

    /**
     * @return Collection
     */
    public function listar_nome_pessoas_juridicas() : Collection
    {
        $canal_pdv_parceiro = new canal_pdv_parceiro();
        $canal_pdv_parceiro = $canal_pdv_parceiro->where('in_canal_pdv_parceiro_ativo', 'S')->orderBy('parceiro_canal_pdv_parceiro', 'ASC');

        return $canal_pdv_parceiro->get();
    }


}
