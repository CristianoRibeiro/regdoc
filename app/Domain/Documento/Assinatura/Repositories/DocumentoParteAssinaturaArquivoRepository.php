<?php

namespace App\Domain\Documento\Assinatura\Repositories;

use stdClass;
use Auth;

use App\Domain\Documento\Assinatura\Models\documento_parte_assinatura_arquivo;

use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaArquivoRepositoryInterface;

class DocumentoParteAssinaturaArquivoRepository implements DocumentoParteAssinaturaArquivoRepositoryInterface
{
    /**
     * @param int $id_documento_parte_assinatura_arquivo
     * @return documento_parte_assinatura_arquivo|null
    */
    public function buscar(int $id_documento_parte_assinatura_arquivo) : ?documento_parte_assinatura_arquivo
    {
        return documento_parte_assinatura_arquivo::find($id_documento_parte_assinatura_arquivo);
    }

    /**
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_parte_assinatura_arquivo
    {
        $novo_documento_parte_assinatura_arquivo = new documento_parte_assinatura_arquivo();
        $novo_documento_parte_assinatura_arquivo->id_documento_parte_assinatura = $args->id_documento_parte_assinatura;
        $novo_documento_parte_assinatura_arquivo->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        $novo_documento_parte_assinatura_arquivo->id_arquivo_grupo_produto_assinatura = $args->id_arquivo_grupo_produto_assinatura ?? NULL;
        $novo_documento_parte_assinatura_arquivo->id_usuario_cad = Auth::User()->id_usuario;
        if (!$novo_documento_parte_assinatura_arquivo->save()) {
            throw new Exception('Erro ao salvar a parte da assinatura do arquivo.');
        }

        return $novo_documento_parte_assinatura_arquivo;
    }

     /**
     * @param documento_parte_assinatura_arquivo $documento_parte_assinatura_arquivo
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
     * @throws Exception
     */
    public function alterar(documento_parte_assinatura_arquivo $documento_parte_assinatura_arquivo, stdClass $args): documento_parte_assinatura_arquivo
    {
        if (isset($args->id_documento_parte_assinatura)) {
            $documento_parte_assinatura_arquivo->id_documento_parte_assinatura = $args->id_documento_parte_assinatura;
        }
        if (isset($args->id_arquivo_grupo_produto)) {
            $documento_parte_assinatura_arquivo->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        }
        if (isset($args->id_arquivo_grupo_produto_assinatura)) {
            $documento_parte_assinatura_arquivo->id_arquivo_grupo_produto_assinatura = $args->id_arquivo_grupo_produto_assinatura;
        }

        if (!$documento_parte_assinatura_arquivo->save()) {
            throw new Exception('Erro ao atualizar a parte da assinatura do arquivo.');
        }

        $documento_parte_assinatura_arquivo->refresh();

        return $documento_parte_assinatura_arquivo;
    }

     /**
     * @param stdClass $args
     * @return documento_parte_assinatura_arquivo
     */
    public function buscar_alterar(stdClass $args): documento_parte_assinatura_arquivo
    {
        $documento_parte_assinatura_arquivo = $this->buscar($args->id_documento_parte_assinatura_arquivo);
        if (!$documento_parte_assinatura_arquivo)
            throw new Exception('A parte da assinatura do arquivo não foi encontrada');

        return $this->alterar($documento_parte_assinatura_arquivo, $args);
    }


}
