<?php

namespace App\Domain\Procuracao\Repositories;

use stdClass;
use Auth;
use Carbon\Carbon;

use App\Domain\Procuracao\Models\procuracao_arquivo_grupo;

use App\Domain\Procuracao\Contracts\ProcuracaoArquivoGrupoRepositoryInterface;

class ProcuracaoArquivoGrupoRepository implements ProcuracaoArquivoGrupoRepositoryInterface
{
    public function inserir(stdClass $args) : procuracao_arquivo_grupo
    {
        $nova_procuracao_arquivo_grupo = new procuracao_arquivo_grupo();
        $nova_procuracao_arquivo_grupo->id_procuracao = $args->id_procuracao;
        $nova_procuracao_arquivo_grupo->id_arquivo_grupo_produto = $args->id_arquivo_grupo_produto;
        $nova_procuracao_arquivo_grupo->in_registro_ativo = $args->in_registro_ativo ?? 'S';
        $nova_procuracao_arquivo_grupo->id_usuario_cad = $args->id_usuario_cad ?? Auth::id();
        $nova_procuracao_arquivo_grupo->dt_cadastro = Carbon::now();

        if (!$nova_procuracao_arquivo_grupo->save()) {
            throw new RegdocException('Erro ao salvar a procuração do arquivo.');
        }

        return $nova_procuracao_arquivo_grupo;
    }
}
