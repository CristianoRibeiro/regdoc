<?php

namespace App\Domain\Arquivo\Contracts;

use stdClass;

use App\Models\arquivo_grupo_produto_assinatura;

interface ArquivoAssinaturaServiceInterface
{
    /**
     * @param stdClass $args
     * @return arquivo_grupo_produto_assinatura
     */
    public function inserir(stdClass $args) : arquivo_grupo_produto_assinatura;
}
