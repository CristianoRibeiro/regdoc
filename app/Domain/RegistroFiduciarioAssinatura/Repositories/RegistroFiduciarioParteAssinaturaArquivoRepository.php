<?php

namespace App\Domain\RegistroFiduciarioAssinatura\Repositories;

use stdClass;
use Auth;
use Exception;

use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface;

use App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura_arquivo;

class RegistroFiduciarioParteAssinaturaArquivoRepository implements RegistroFiduciarioParteAssinaturaArquivoRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_parte_assinatura_arquivo
     * @return registro_fiduciario_parte_assinatura_arquivo|null
     */
    public function buscar(int $id_registro_fiduciario_parte_assinatura_arquivo): ?registro_fiduciario_parte_assinatura_arquivo
    {
        return registro_fiduciario_parte_assinatura_arquivo::find($id_registro_fiduciario_parte_assinatura_arquivo);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura_arquivo
     * @throws Exception
     */
    public function inserir(stdClass $args): registro_fiduciario_parte_assinatura_arquivo
    {
        $registro_fiduciario_parte_assinatura_arquivo = new registro_fiduciario_parte_assinatura_arquivo();
        $registro_fiduciario_parte_assinatura_arquivo->id_registro_fiduciario_parte_assinatura = $args->id_registro_fiduciario_parte_assinatura;
        $registro_fiduciario_parte_assinatura_arquivo->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        $registro_fiduciario_parte_assinatura_arquivo->id_usuario_cad = Auth::id();

        if (!$registro_fiduciario_parte_assinatura_arquivo->save()) {
            throw new Exception('Erro ao salvar o arquivo da assinatura.');
        }

        return $registro_fiduciario_parte_assinatura_arquivo;
    }

    /**
     * @param registro_fiduciario_parte_assinatura_arquivo $registro_fiduciario_parte_assinatura_arquivo
     * @param stdClass $args
     * @return registro_fiduciario_parte_assinatura_arquivo
     * @throws Exception
     */
    public function alterar(registro_fiduciario_parte_assinatura_arquivo $registro_fiduciario_parte_assinatura_arquivo, stdClass $args): registro_fiduciario_parte_assinatura_arquivo
    {
        if (isset($args->id_arquivo_grupo_produto_assinatura)) {
            $registro_fiduciario_parte_assinatura_arquivo->id_arquivo_grupo_produto_assinatura = $args->id_arquivo_grupo_produto_assinatura;
        }

        if (!$registro_fiduciario_parte_assinatura_arquivo->save()) {
            throw new Exception('Erro ao atualizar o arquivo da assinatura.');
        }

        $registro_fiduciario_parte_assinatura_arquivo->refresh();

        return $registro_fiduciario_parte_assinatura_arquivo;
    }
}
