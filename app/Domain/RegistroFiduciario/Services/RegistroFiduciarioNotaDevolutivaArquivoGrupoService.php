<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_arquivo_grupo;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface;

class RegistroFiduciarioNotaDevolutivaArquivoGrupoService implements RegistroFiduciarioNotaDevolutivaArquivoGrupoServiceInterface
{
    /**
     * @var RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface
     */
    protected $RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface;

    /**
     * RegistroFiduciarioNotaDevolutivaArquivoGrupoService constructor.
     * @param RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface $RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface $RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface)
    {
        $this->RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface = $RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva_arquivo_grupo
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva_arquivo_grupo): ?registro_fiduciario_nota_devolutiva_arquivo_grupo
    {
        return $this->RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface->buscar($id_registro_fiduciario_nota_devolutiva_arquivo_grupo);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo
     */
    public function inserir(stdClass $args): registro_fiduciario_nota_devolutiva_arquivo_grupo
    {
        return $this->RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_nota_devolutiva_arquivo_grupo $registro_fiduciario_nota_devolutiva_arquivo_grupo
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva_arquivo_grupo
     */
    public function alterar(registro_fiduciario_nota_devolutiva_arquivo_grupo $registro_fiduciario_nota_devolutiva_arquivo_grupo, stdClass $args) : registro_fiduciario_nota_devolutiva_arquivo_grupo
    {
        return $this->RegistroFiduciarioNotaDevolutivaArquivoGrupoRepositoryInterface->alterar($registro_fiduciario_nota_devolutiva_arquivo_grupo, $args);
    }
}
