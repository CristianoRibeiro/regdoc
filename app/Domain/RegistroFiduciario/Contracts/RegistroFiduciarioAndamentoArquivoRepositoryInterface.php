<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use App\Models\arquivo_grupo_produto;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento_arquivo_grupo;

interface RegistroFiduciarioAndamentoArquivoRepositoryInterface
{
    public function inserir_relacao(registro_fiduciario_andamento $registro_fiduciario_andamento, arquivo_grupo_produto $arquivo_grupo_produto, string $in_acao, string $in_resultado) : registro_fiduciario_andamento_arquivo_grupo;
}
