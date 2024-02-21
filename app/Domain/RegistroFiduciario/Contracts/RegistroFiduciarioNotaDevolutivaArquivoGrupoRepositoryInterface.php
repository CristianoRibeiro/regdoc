<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_arquivo_grupo;

interface RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva_arquivo_grupo
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva_arquivo_grupo) : ?registro_fiduciario_nota_devolutiva_arquivo_grupo;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo
     */
    public function inserir(stdClass $args) : registro_fiduciario_nota_devolutiva_arquivo_grupo;

    /**
     * @param registro_fiduciario_nota_devolutiva_arquivo_grupo $registro_fiduciario_nota_devolutiva_arquivo_grupo
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo
     */
    public function alterar(registro_fiduciario_nota_devolutiva_arquivo_grupo $registro_fiduciario_nota_devolutiva_arquivo_grupo, stdClass $args) : registro_fiduciario_nota_devolutiva_arquivo_grupo;
}
