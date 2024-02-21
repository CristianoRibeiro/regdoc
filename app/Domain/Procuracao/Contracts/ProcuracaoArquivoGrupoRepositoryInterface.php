<?php

namespace App\Domain\Procuracao\Contracts;

use stdClass;

use App\Domain\Procuracao\Models\procuracao_arquivo_grupo;

interface ProcuracaoArquivoGrupoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return procuracao_arquivo_grupo
     */
    public function inserir(stdClass $args) : procuracao_arquivo_grupo;
}
