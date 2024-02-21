<?php

namespace App\Domain\Arquivo\Repositories;

use stdClass;

use App\Models\arquivo_grupo_produto_assinatura;

use App\Domain\Arquivo\Contracts\ArquivoAssinaturaRepositoryInterface;

class ArquivoAssinaturaRepository implements ArquivoAssinaturaRepositoryInterface
{
    public function inserir(stdClass $args) : arquivo_grupo_produto_assinatura
    {
        $novo_arquivo_assinatura = new arquivo_grupo_produto_assinatura();
        $novo_arquivo_assinatura->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        $novo_arquivo_assinatura->no_arquivo = $args->no_arquivo;
        $novo_arquivo_assinatura->no_local_arquivo = $args->no_local_arquivo;
        $novo_arquivo_assinatura->no_extensao = $args->no_extensao;
        $novo_arquivo_assinatura->in_ass_digital = $args->in_ass_digital ?? 'N';
        $novo_arquivo_assinatura->dt_ass_digital = $args->dt_ass_digital ?? NULL;
        $novo_arquivo_assinatura->nu_tamanho_kb = $args->nu_tamanho_kb;
        $novo_arquivo_assinatura->no_hash = $args->no_hash;
        $novo_arquivo_assinatura->no_arquivo_p7s = $args->no_arquivo_p7s ?? NULL;
        $novo_arquivo_assinatura->no_hash_p7s = $args->no_hash_p7s ?? NULL;
        $novo_arquivo_assinatura->id_usuario_certificado = $args->id_usuario_certificado ?? NULL;
        $novo_arquivo_assinatura->no_mime_type = $args->no_mime_type;
        $novo_arquivo_assinatura->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();

        if (!$novo_arquivo_assinatura->save()) {
            throw new RegdocException('Erro ao salvar a assinatura do arquivo.');
        }

        return $novo_arquivo_assinatura;
    }
}
