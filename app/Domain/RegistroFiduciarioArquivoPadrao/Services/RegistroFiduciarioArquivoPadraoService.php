<?php

namespace App\Domain\RegistroFiduciarioArquivoPadrao\Services;

use Illuminate\Database\Eloquent\Collection;
use stdClass;

use App\Domain\RegistroFiduciarioArquivoPadrao\Models\registro_fiduciario_arquivo_padrao;

use App\Domain\RegistroFiduciarioArquivoPadrao\Contracts\RegistroFiduciarioArquivoPadraoRepositoryInterface;
use App\Domain\RegistroFiduciarioArquivoPadrao\Contracts\RegistroFiduciarioArquivoPadraoServiceInterface;

class RegistroFiduciarioArquivoPadraoService implements RegistroFiduciarioArquivoPadraoServiceInterface
{
    /**
     * @var RegistroFiduciarioArquivoPadraoRepositoryInterface
     */
    protected $RegistroFiduciarioArquivoPadraoRepositoryInterface;

    /**
     * RegistroFiduciarioArquivoPadraoService constructor.
     * @param RegistroFiduciarioArquivoPadraoRepositoryInterface $RegistroFiduciarioArquivoPadraoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioArquivoPadraoRepositoryInterface $RegistroFiduciarioArquivoPadraoRepositoryInterface)
    {
        $this->RegistroFiduciarioArquivoPadraoRepositoryInterface = $RegistroFiduciarioArquivoPadraoRepositoryInterface;
    }

    /**
     * @param int $id_pessoa = NULL
     * @param int $id_registro_fiduciario_tipo = NULL
     * @param int $id_tipo_arquivo_grupo_produto = NULL
     * @param string $nu_cpf_cnpj = NULL
     * @return Collection
     */
    public function listar(int $id_pessoa = NULL, int $id_registro_fiduciario_tipo = NULL, int $id_tipo_arquivo_grupo_produto = NULL, string $nu_cpf_cnpj = NULL): Collection
    {
        return $this->RegistroFiduciarioArquivoPadraoRepositoryInterface->listar($id_pessoa, $id_registro_fiduciario_tipo, $id_tipo_arquivo_grupo_produto, $nu_cpf_cnpj);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_padrao
     */
    public function inserir(stdClass $args): registro_fiduciario_arquivo_padrao
    {
        return $this->RegistroFiduciarioArquivoPadraoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_arquivo_padrao $registro_fiduciario_arquivo_padrao
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_padrao
     */
    public function alterar(registro_fiduciario_arquivo_padrao $registro_fiduciario_arquivo_padrao, stdClass $args): registro_fiduciario_arquivo_padrao
    {
        return $this->RegistroFiduciarioArquivoPadraoRepositoryInterface->alterar($registro_fiduciario_arquivo_padrao, $args);
    }
}
