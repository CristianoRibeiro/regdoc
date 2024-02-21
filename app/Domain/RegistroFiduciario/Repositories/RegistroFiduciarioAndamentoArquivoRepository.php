<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Carbon\Carbon;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoArquivoRepositoryInterface;

use App\Exceptions\RegdocException;

use App\Models\arquivo_grupo_produto;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento_arquivo_grupo;

class RegistroFiduciarioAndamentoArquivoRepository implements RegistroFiduciarioAndamentoArquivoRepositoryInterface
{
    public function inserir_relacao(registro_fiduciario_andamento $registro_fiduciario_andamento, arquivo_grupo_produto $arquivo_grupo_produto, string $in_acao, string $in_resultado) : registro_fiduciario_andamento_arquivo_grupo
    {
        $nova_relacao = new registro_fiduciario_andamento_arquivo_grupo();
        $nova_relacao->id_registro_fiduciario_andamento = $registro_fiduciario_andamento->id_registro_fiduciario_andamento;
        $nova_relacao->id_arquivo_grupo_produto = $arquivo_grupo_produto->id_arquivo_grupo_produto;
        $nova_relacao->in_acao = $in_acao;
        $nova_relacao->in_resultado = $in_resultado;

        if (!$nova_relacao->save()) {
            throw new RegdocException('Erro ao salvar a relação do arquivo.');
        }
        return $nova_relacao;
    }
}
