<?php

namespace App\Domain\Arquivo\Repositories;

use App\Domain\Arquivo\Contracts\ArquivoRepositoryInterface;

use Auth;
use stdClass;
use Exception;
use Ramsey\Uuid\Uuid;

use App\Domain\Arquivo\Models\arquivo_grupo_produto;

class ArquivoRepository implements ArquivoRepositoryInterface
{
    /**
     * @param int $id_arquivo_grupo_produto
     * @return arquivo_grupo_produto|null
     */
    public function buscar(int $id_arquivo_grupo_produto) : ?arquivo_grupo_produto
    {
        return arquivo_grupo_produto::findOrFail($id_arquivo_grupo_produto);
    }

    /**
     * @param string $uuid
     * @return arquivo_grupo_produto|null
     */
    public function buscar_uuid(string $uuid) : ?arquivo_grupo_produto
    {
        return arquivo_grupo_produto::where('uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return arquivo_grupo_produto
     */
    public function inserir(stdClass $args) : arquivo_grupo_produto
    {
        $novo_arquivo = new arquivo_grupo_produto();
        $novo_arquivo->uuid = Uuid::uuid4();
        $novo_arquivo->id_grupo_produto = $args->id_grupo_produto;
        $novo_arquivo->id_tipo_arquivo_grupo_produto = $args->id_tipo_arquivo_grupo_produto;
        $novo_arquivo->no_arquivo = $args->no_arquivo;
        $novo_arquivo->no_descricao_arquivo = $args->no_descricao_arquivo;
        $novo_arquivo->no_local_arquivo = $args->no_local_arquivo;
        $novo_arquivo->id_usuario_cad = $args->id_usuario ?? Auth::id();
        $novo_arquivo->no_extensao = $args->no_extensao;
        $novo_arquivo->nu_tamanho_kb = $args->nu_tamanho_kb;
        $novo_arquivo->no_hash = $args->no_hash;
        $novo_arquivo->no_mime_type = $args->no_mime_type;
        $novo_arquivo->no_url_origem = $args->no_url_origem ?? NULL;

        if (!$novo_arquivo->save()) {
            throw new Exception('Erro ao salvar o arquivo.');
        }

        return $novo_arquivo;
    }

    /**
     * @param arquivo_grupo_produto $arquivo_grupo_produto
     * @param stdClass $args
     * @return arquivo_grupo_produto
     */
    public function alterar(arquivo_grupo_produto $arquivo_grupo_produto, stdClass $args) : arquivo_grupo_produto
    {
        if(isset($args->no_arquivo)) {
            $arquivo_grupo_produto->no_arquivo = $args->no_arquivo;
        }
        if(isset($args->no_descricao_arquivo)) {
            $arquivo_grupo_produto->no_descricao_arquivo = $args->no_descricao_arquivo;
        }
        if(isset($args->no_local_arquivo)) {
            $arquivo_grupo_produto->no_local_arquivo = $args->no_local_arquivo;
        }
        if(isset($args->nu_tamanho_kb)) {
            $arquivo_grupo_produto->nu_tamanho_kb = $args->nu_tamanho_kb;
        }
        if(isset($args->no_hash)) {
            $arquivo_grupo_produto->no_hash = $args->no_hash;
        }
        if(isset($args->no_url_origem)) {
            $arquivo_grupo_produto->no_url_origem = $args->no_url_origem ?? NULL;
        }
        if(isset($args->in_enviar_arquivo)) {
            $arquivo_grupo_produto->in_enviar_arquivo = $args->in_enviar_arquivo ?? NULL;
        }
        if(isset($args->in_ass_digital)) {
            $arquivo_grupo_produto->in_ass_digital = $args->in_ass_digital;
        }
        if(isset($args->dt_ass_digital)) {
            $arquivo_grupo_produto->dt_ass_digital = $args->dt_ass_digital;
        }

        if (!$arquivo_grupo_produto->save()) {
            throw new Exception('Erro ao atualizar o arquivo.');
        }

        $arquivo_grupo_produto->refresh();

        return $arquivo_grupo_produto;
    }
}
