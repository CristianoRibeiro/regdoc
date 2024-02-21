<?php

namespace App\Domain\Procuracao\Services;

use stdClass;

use App\Domain\Procuracao\Models\procuracao_arquivo_grupo;

use App\Domain\Procuracao\Contracts\ProcuracaoArquivoGrupoServiceInterface;
use App\Domain\Procuracao\Repositories\ProcuracaoArquivoGrupoRepository;

class ProcuracaoArquivoGrupoService implements ProcuracaoArquivoGrupoServiceInterface
{
    public function __construct(ProcuracaoArquivoGrupoRepository $ProcuracaoArquivoGrupoRepository)
    {
        $this->ProcuracaoArquivoGrupoRepository = $ProcuracaoArquivoGrupoRepository;
    }

    public function inserir(stdClass $args) : procuracao_arquivo_grupo
    {
        return $this->ProcuracaoArquivoGrupoRepository->inserir($args);
    }
}
