<?php

namespace App\Domain\Arquivo\Contracts;

use stdClass;

use App\Domain\Arquivo\Models\arquivo_grupo_produto;

interface ArquivoServiceInterface
{
    /**
     * @param int $id_arquivo_grupo_produto
     * @return arquivo_grupo_produto|null
     */
    public function buscar(int $id_arquivo_grupo_produto) : ?arquivo_grupo_produto;

    /**
     * @param string $uuid
     * @return arquivo_grupo_produto|null
     */
    public function buscar_uuid(string $uuid) : ?arquivo_grupo_produto;

    /**
     * @param stdClass $args
     * @return arquivo_grupo_produto
     */
    public function inserir(stdClass $args) : arquivo_grupo_produto;

    /**
     * @param arquivo_grupo_produto $arquivo_grupo_produto
     * @param stdClass $args
     * @return arquivo_grupo_produto
     */
    public function alterar(arquivo_grupo_produto $arquivo_grupo_produto, stdClass $args) : arquivo_grupo_produto;
}
