<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Exception;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioArquivoGrupoProdutoServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_arquivo_grupo_produto;

class RegistroFiduciarioArquivoGrupoProdutoService implements RegistroFiduciarioArquivoGrupoProdutoServiceInterface
{
    /**
     * @var RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface
     */
    protected $RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface;

    /**
     * RegistroFiduciarioArquivoGrupoProdutoService constructor.
     * @param RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface $RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface $RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface)
    {
        $this->RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface = $RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface;
    }

    /**
     * @param registro_fiduciario_arquivo_grupo_produto $registro_fiduciario_arquivo_grupo_produto
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_grupo_produto
     * @throws Exception
     */
    public function alterar($registro_fiduciario_arquivo_grupo_produto, stdClass $args) : registro_fiduciario_arquivo_grupo_produto
    {
        return $this->RegistroFiduciarioArquivoGrupoProdutoRepositoryInterface->alterar($registro_fiduciario_arquivo_grupo_produto, $args);
    }
}
