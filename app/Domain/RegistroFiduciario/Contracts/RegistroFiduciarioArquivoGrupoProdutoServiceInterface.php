<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Exception;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_arquivo_grupo_produto;

interface RegistroFiduciarioArquivoGrupoProdutoServiceInterface
{
    /**
     * @param registro_fiduciario_arquivo_grupo_produto $registro_fiduciario_arquivo_grupo_produto
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_grupo_produto
     * @throws Exception
     */
    public function alterar($registro_fiduciario_arquivo_grupo_produto, stdClass $args) : registro_fiduciario_arquivo_grupo_produto;
}
