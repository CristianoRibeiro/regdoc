<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Exception;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_arquivo_grupo_produto;

class RegistroFiduciarioArquivoGrupoProdutoRepository implements RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface
{

    /**
     * @param registro_fiduciario_arquivo_grupo_produto $registro_fiduciario_arquivo_grupo_produto
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_grupo_produto
     * @throws Exception
     */
    public function alterar($registro_fiduciario_arquivo_grupo_produto, stdClass $args) : registro_fiduciario_arquivo_grupo_produto
    {
        if(isset($args->in_registro_ativo)) {
            $registro_fiduciario_arquivo_grupo_produto->in_registro_ativo = $args->in_registro_ativo;
        }
        if (!$registro_fiduciario_arquivo_grupo_produto->save()) {
            throw new Exception('Erro ao atualizar o arquivo do contrato do registro.');
        }

        $registro_fiduciario_arquivo_grupo_produto->refresh();

        return $registro_fiduciario_arquivo_grupo_produto;
    }
}
