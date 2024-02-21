<?php

namespace App\Domain\Pessoa\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;

use App\Models\telefone;
use App\Domain\Pessoa\Models\pessoa;

use App\Domain\Pessoa\Contracts\PessoaRepositoryInterface;

class PessoaRepository implements PessoaRepositoryInterface
{
    /**
     * @param array $tipos_pessoa
     * @param array $tipos_serventia
     * @param int $id_cidade
     * @return Collection
     */
    public function pessoa_disponiveis(array $tipos_pessoa = [], array $tipos_serventia = [], int $id_cidade = 0) : Collection
    {
        $pessoas = pessoa::select('pessoa.*');

        if ($id_cidade) {
            $pessoas = $pessoas->join('pessoa_endereco', 'pessoa.id_pessoa', '=', 'pessoa_endereco.id_pessoa')
                ->join('endereco', 'pessoa_endereco.id_endereco', '=', 'endereco.id_endereco')
                ->where('endereco.id_cidade', $id_cidade);
        }
        if ($tipos_pessoa) {
            $pessoas = $pessoas->whereIn('pessoa.id_tipo_pessoa', $tipos_pessoa);
        }
        if ($tipos_serventia) {
            $pessoas = $pessoas->join('serventia', 'serventia.id_pessoa', '=', 'pessoa.id_pessoa')
                ->whereIn('serventia.id_tipo_serventia', $tipos_serventia);
        }

        $pessoas = $pessoas->orderBy('no_pessoa')
            ->get();

        return $pessoas;
    }

    /**
     * @param int $id_pessoa
     * @return pessoa|null
     */
    public function buscar(int $id_pessoa) : ?pessoa
    {
        return pessoa::find($id_pessoa);
    }

    /**
     * @param stdClass $args
     * @return pessoa
     * @throws Exception
     */
    public function inserir(stdClass $args): pessoa
    {
        $nova_pessoa = new pessoa();
        $nova_pessoa->no_pessoa = $args->no_pessoa;
        $nova_pessoa->tp_pessoa = $args->tp_pessoa;
        $nova_pessoa->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $nova_pessoa->nu_rg = $args->nu_rg ?? NULL;
        $nova_pessoa->no_orgao_emissor_rg = $args->no_orgao_emissor_rg ?? NULL;
        $nova_pessoa->dt_emissao_rg = $args->dt_emissao_rg ?? NULL;
        $nova_pessoa->nu_cnh = $args->nu_cnh ?? NULL;
        $nova_pessoa->nu_passaporte = $args->nu_passaporte ?? NULL;
        $nova_pessoa->nu_outro_documento = $args->nu_outro_documento ?? NULL;
        $nova_pessoa->nu_inscricao_estadual = $args->nu_inscricao_estadual ?? NULL;
        $nova_pessoa->nu_inscricao_municipal = $args->nu_inscricao_municipal ?? NULL;
        $nova_pessoa->no_fantasia = $args->no_fantasia ?? NULL;
        $nova_pessoa->tp_sexo = $args->tp_sexo ?? 'N';
        $nova_pessoa->no_email_pessoa = mb_strtolower($args->no_email_pessoa ?? NULL, 'UTF-8');
        $nova_pessoa->dt_nascimento = $args->dt_nascimento ?? NULL;
        $nova_pessoa->id_cidade_nascimento = $args->id_cidade_nascimento ?? NULL;
        $nova_pessoa->id_estado_civil = $args->id_estado_civil ?? NULL;
        $nova_pessoa->id_nacionalidade = $args->id_nacionalidade ?? NULL;
        $nova_pessoa->id_pais = $args->id_pais ?? NULL;
        $nova_pessoa->co_uf_nascimento = $args->co_uf_nascimento ?? NULL;
        $nova_pessoa->in_registro_ativo = $args->in_registro_ativo ?? 'S';
        $nova_pessoa->id_tipo_pessoa = $args->id_tipo_pessoa;

        if (!$nova_pessoa->save()) {
            throw new Exception('Erro ao salvar a pessoa.');
        }

        return $nova_pessoa;
    }

    /**
     * @param pessoa $pessoa
     * @param stdClass $args
     * @return pessoa
     * @throws Exception
     */
    public function alterar(pessoa $pessoa, stdClass $args) : pessoa
    {
        if (isset($args->no_pessoa)) {
            $pessoa->no_pessoa = $args->no_pessoa;
        }
        if (isset($args->no_email_pessoa)) {
            $pessoa->no_email_pessoa = $args->no_email_pessoa;
        }
        if (isset($args->tp_pessoa)) {
            $pessoa->tp_pessoa = $args->tp_pessoa;
        }
        if (isset($args->nu_cpf_cnpj)) {
            $pessoa->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        }
        if (isset($args->in_registro_ativo)) {
            $pessoa->in_registro_ativo = $args->in_registro_ativo;
        }
        if (!$pessoa->save()) {
            throw new Exception('Erro ao atualizar o usuário.');
        }

        $pessoa->refresh();

        return $pessoa;
    }

    /**
     * @return Collection
     */
    public function lista_entidades() : Collection
    {
        return pessoa::where([['id_tipo_pessoa', '=', 8], ['in_registro_ativo', '=', 'S']])
            ->get();
    }

    /**
     * @param array $id_tipo_pessoas
     * @return Collection
     */
    public function listar_por_tipo(array $id_tipo_pessoas) : Collection
    {
        return pessoa::whereIn('id_tipo_pessoa', $id_tipo_pessoas)
            ->where('in_registro_ativo', '=', 'S')
            ->get();
    }

    /**
     * @param string $cnpj
     * @return Collection
     */

    public function buscar_por_cpf_cnpj(string $cnpj) : pessoa
    {
        return pessoa::firstWhere('nu_cpf_cnpj', $cnpj);
    }
}
