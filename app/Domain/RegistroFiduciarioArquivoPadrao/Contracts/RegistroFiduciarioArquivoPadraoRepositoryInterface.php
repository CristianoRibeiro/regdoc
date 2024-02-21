<?php

namespace App\Domain\RegistroFiduciarioArquivoPadrao\Contracts;

use Illuminate\Database\Eloquent\Collection;
use stdClass;

use App\Domain\RegistroFiduciarioArquivoPadrao\Models\registro_fiduciario_arquivo_padrao;

interface RegistroFiduciarioArquivoPadraoRepositoryInterface
{
    /**
     * @param int $id_pessoa = NULL
     * @param int $id_registro_fiduciario_tipo = NULL
     * @param int $id_tipo_arquivo_grupo_produto = NULL
     * @param string $nu_cpf_cnpj = NULL
     * @return Collection
     */
    public function listar(int $id_pessoa = NULL, int $id_registro_fiduciario_tipo = NULL, int $id_tipo_arquivo_grupo_produto = NULL, string $nu_cpf_cnpj = NULL): Collection;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_padrao
     */
    public function inserir(stdClass $args) : registro_fiduciario_arquivo_padrao;

    /**
     * @param registro_fiduciario_arquivo_padrao $registro_fiduciario_arquivo_padrao
     * @param stdClass $args
     * @return registro_fiduciario_arquivo_padrao
     */
    public function alterar(registro_fiduciario_arquivo_padrao $registro_fiduciario_arquivo_padrao, stdClass $args): registro_fiduciario_arquivo_padrao;
}
