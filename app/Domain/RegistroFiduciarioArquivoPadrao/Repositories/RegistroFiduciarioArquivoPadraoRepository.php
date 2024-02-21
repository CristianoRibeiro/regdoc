<?php

namespace App\Domain\RegistroFiduciarioArquivoPadrao\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Exception;
use stdClass;

use App\Domain\RegistroFiduciarioArquivoPadrao\Models\registro_fiduciario_arquivo_padrao;

use App\Domain\RegistroFiduciarioArquivoPadrao\Contracts\RegistroFiduciarioArquivoPadraoRepositoryInterface;

class RegistroFiduciarioArquivoPadraoRepository implements RegistroFiduciarioArquivoPadraoRepositoryInterface
{
    /**
     * @param int $id_pessoa = NULL
     * @param int $id_registro_fiduciario_tipo = NULL
     * @param int $id_tipo_arquivo_grupo_produto = NULL
     * @param string $nu_cpf_cnpj = NULL
     * @return Collection
     */
    public function listar(int $id_pessoa = NULL, int $id_registro_fiduciario_tipo = NULL, int $id_tipo_arquivo_grupo_produto = NULL, string $nu_cpf_cnpj = NULL): Collection
    {
        $registro_fiduciario_arquivo_padrao = registro_fiduciario_arquivo_padrao::where('in_registro_ativo', 'S');
        if(!is_null($id_pessoa)) {
            $registro_fiduciario_arquivo_padrao = $registro_fiduciario_arquivo_padrao->where('id_pessoa', $id_pessoa);
        }
        if(!is_null($id_registro_fiduciario_tipo)) {
            $registro_fiduciario_arquivo_padrao = $registro_fiduciario_arquivo_padrao->where('id_registro_fiduciario_tipo', $id_registro_fiduciario_tipo);
        }
        if(!is_null($id_tipo_arquivo_grupo_produto)) {
            $registro_fiduciario_arquivo_padrao = $registro_fiduciario_arquivo_padrao->where('id_tipo_arquivo_grupo_produto', $id_tipo_arquivo_grupo_produto);
        }
        if(!is_null($nu_cpf_cnpj)) {
            $registro_fiduciario_arquivo_padrao = $registro_fiduciario_arquivo_padrao->where('nu_cpf_cnpj', $nu_cpf_cnpj);
        } else {
            $registro_fiduciario_arquivo_padrao = $registro_fiduciario_arquivo_padrao->whereNull('nu_cpf_cnpj');
        }
        return $registro_fiduciario_arquivo_padrao->get();
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_padrao
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_arquivo_padrao
    {
        $registro_fiduciario_arquivo_padrao = new registro_fiduciario_arquivo_padrao();
        $registro_fiduciario_arquivo_padrao->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        $registro_fiduciario_arquivo_padrao->id_registro_fiduciario_tipo = $args->id_registro_fiduciario_tipo;
        $registro_fiduciario_arquivo_padrao->id_pessoa = $args->id_pessoa;
        $registro_fiduciario_arquivo_padrao->id_tipo_arquivo_grupo_produto = $args->id_tipo_arquivo_grupo_produto;
        $registro_fiduciario_arquivo_padrao->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $registro_fiduciario_arquivo_padrao->id_usuario_cad = Auth::id();

        if (!$registro_fiduciario_arquivo_padrao->save()) {
            throw new Exception('Erro ao salvar o arquivo padrão.');
        }

        return $registro_fiduciario_arquivo_padrao;
    }

    /**
     * @param registro_fiduciario_arquivo_padrao $registro_fiduciario_arquivo_padrao
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_padrao
     * @throws Exception
     */
    public function alterar(registro_fiduciario_arquivo_padrao $registro_fiduciario_arquivo_padrao, stdClass $args): registro_fiduciario_arquivo_padrao
    {
        if (isset($args->id_registro_fiduciario_tipo)) {
            $registro_fiduciario_arquivo_padrao->id_registro_fiduciario_tipo = $args->id_registro_fiduciario_tipo;
        }
        if (isset($args->id_pessoa)) {
            $registro_fiduciario_arquivo_padrao->id_pessoa = $args->id_pessoa;
        }
        if (isset($args->id_tipo_arquivo_grupo_produto)) {
            $registro_fiduciario_arquivo_padrao->id_tipo_arquivo_grupo_produto = $args->id_tipo_arquivo_grupo_produto;
        }
        if (isset($args->nu_cpf_cnpj)) {
            $registro_fiduciario_arquivo_padrao->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        }

        if (!$registro_fiduciario_arquivo_padrao->save()) {
            throw new Exception('Erro ao atualizar o arquivo padrão.');
        }

        $registro_fiduciario_arquivo_padrao->refresh();

        return $registro_fiduciario_arquivo_padrao;
    }
}
