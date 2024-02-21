<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_situacao;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaSituacaoServiceInterface;

class RegistroFiduciarioNotaDevolutivaSituacaoService implements RegistroFiduciarioNotaDevolutivaSituacaoServiceInterface
{
    /**
     * @var RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface
     */
    protected $RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface;

    /**
     * RegistroFiduciarioNotaDevolutivaSituacaoService constructor.
     * @param RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface $RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface $RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface)
    {
        $this->RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface = $RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva_situacao
     * @return registro_fiduciario_nota_devolutiva_situacao|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva_situacao): ?registro_fiduciario_nota_devolutiva_situacao
    {
        return $this->RegistroFiduciarioPagamentoRepositoryInterface->buscar($id_registro_fiduciario_nota_devolutiva_situacao);
    }

}
