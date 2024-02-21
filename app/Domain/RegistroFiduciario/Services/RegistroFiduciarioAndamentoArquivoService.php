<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAndamentoArquivoServiceInterface;
use App\Domain\RegistroFiduciario\Repositories\RegistroFiduciarioAndamentoArquivoRepository;

use App\Models\arquivo_grupo_produto;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento_arquivo_grupo;

class RegistroFiduciarioAndamentoArquivoService implements RegistroFiduciarioAndamentoArquivoServiceInterface
{
    public function __construct(RegistroFiduciarioAndamentoArquivoRepository $RegistroFiduciarioAndamentoArquivoRepository)
    {
        $this->RegistroFiduciarioAndamentoArquivoRepository = $RegistroFiduciarioAndamentoArquivoRepository;
    }

    public function inserir_relacao(registro_fiduciario_andamento $registro_fiduciario_andamento, arquivo_grupo_produto $arquivo_grupo_produto, string $in_acao, string $in_resultado) : registro_fiduciario_andamento_arquivo_grupo
    {
        return $this->RegistroFiduciarioAndamentoArquivoRepository->inserir_relacao($registro_fiduciario_andamento, $arquivo_grupo_produto, $in_acao, $in_resultado);
    }
}
