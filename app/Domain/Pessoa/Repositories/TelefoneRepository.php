<?php

namespace App\Domain\Pessoa\Repositories;

use App\Domain\Pessoa\Contracts\TelefoneRepositoryInterface;
use App\Domain\Pessoa\Models\telefone;
use stdClass;
use Exception;

class TelefoneRepository implements TelefoneRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return telefone
     * @throws Exception
     */
    public function insere(stdClass $args): telefone
    {
        $args_telefone = new telefone();
        $args_telefone->id_tipo_telefone = $args->id_tipo_telefone;
        $args_telefone->id_classificacao_telefone = $args->id_classificacao_telefone;
        $args_telefone->nu_ddi = $args->nu_ddi;
        $args_telefone->nu_ddd = $args->nu_ddd;
        $args_telefone->nu_telefone = $args->nu_telefone;
        $args_telefone->in_registro_ativo = $args->in_registro_ativo ?? 'S';

        if (!$args_telefone->save()) {
            throw new Exception('Erro ao inserir o telefone.');
        }

        return $args_telefone;
    }
}