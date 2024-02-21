<?php

namespace App\Domain\Arquivo\Services;

use stdClass;

use App\Models\arquivo_grupo_produto_assinatura;

use App\Domain\Arquivo\Contracts\ArquivoAssinaturaServiceInterface;
use App\Domain\Arquivo\Repositories\ArquivoAssinaturaRepository;

class ArquivoAssinaturaService implements ArquivoAssinaturaServiceInterface
{
    public function __construct(ArquivoAssinaturaRepository $ArquivoAssinaturaRepository)
    {
        $this->ArquivoAssinaturaRepository = $ArquivoAssinaturaRepository;
    }

    public function inserir(stdClass $args) : arquivo_grupo_produto_assinatura
    {
        return $this->ArquivoAssinaturaRepository->inserir($args);
    }
}
