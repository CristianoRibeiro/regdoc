<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioConjugeRepositoryInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_conjuge;

class RegistroFiduciarioConjugeRepository implements RegistroFiduciarioConjugeRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_conjuge
     */
    public function inserir(stdClass $args): registro_fiduciario_conjuge
    {
        $conjuge = new registro_fiduciario_conjuge();
        $conjuge->no_conjuge = $args->no_conjuge;
        $conjuge->no_nacionalidade = $args->no_nacionalidade;
        $conjuge->no_profissao = $args->no_profissao;
        $conjuge->no_tipo_documento = $args->no_tipo_documento;
        $conjuge->numero_documento = $args->numero_documento;
        $conjuge->no_orgao_expedidor_documento = $args->no_orgao_expedidor_documento;
        $conjuge->uf_orgao_expedidor_documento = $args->uf_orgao_expedidor_documento;
        $conjuge->dt_expedicao_documento = $args->dt_expedicao_documento;
        $conjuge->nu_cpf = $args->nu_cpf;
        $conjuge->no_endereco = $args->no_endereco;
        $conjuge->nu_telefone_contato = $args->nu_telefone_contato;
        $conjuge->no_email_contato = mb_strtolower($args->no_email_contato, 'UTF-8');
        $conjuge->id_usuario_cad = Auth::User()->id_usuario;
        if (!$conjuge->save()) {
            throw new Exception('Erro ao salvar o cônjuge da parte.');
        }

        return $conjuge;
    }
}
