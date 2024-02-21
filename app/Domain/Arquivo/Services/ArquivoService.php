<?php

namespace App\Domain\Arquivo\Services;

use stdClass;

use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoRepositoryInterface;

use App\Domain\Arquivo\Models\arquivo_grupo_produto;

class ArquivoService implements ArquivoServiceInterface
{
    /**
     * @var $ArquivoRepositoryInterface
     */
    protected $ArquivoRepositoryInterface;

    /**
     * ArquivoService constructor.
     * @param ArquivoRepositoryInterface $ArquivoRepositoryInterface
     */

    public function __construct(ArquivoRepositoryInterface $ArquivoRepositoryInterface)
    {
        $this->ArquivoRepositoryInterface = $ArquivoRepositoryInterface;
    }

    /**
     * @param int $id_arquivo_grupo_produto
     * @return arquivo_grupo_produto|null
     */
    public function buscar(int $id_arquivo_grupo_produto) : ?arquivo_grupo_produto
    {
        return $this->ArquivoRepositoryInterface->buscar($id_arquivo_grupo_produto);
    }

    /**
     * @param string $uuid
     * @return arquivo_grupo_produto|null
     */
    public function buscar_uuid(string $uuid) : ?arquivo_grupo_produto
    {
        return $this->ArquivoRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return arquivo_grupo_produto
     */
    public function inserir(stdClass $args) : arquivo_grupo_produto
    {
        return $this->ArquivoRepositoryInterface->inserir($args);
    }

    /**
     * @param arquivo_grupo_produto $arquivo_grupo_produto
     * @param stdClass $args
     * @return arquivo_grupo_produto
     */
    public function alterar(arquivo_grupo_produto $arquivo_grupo_produto, stdClass $args) : arquivo_grupo_produto
    {
        return $this->ArquivoRepositoryInterface->alterar($arquivo_grupo_produto, $args);
    }
}
