<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Exception;
use Auth;
use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_situacao;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface;

class RegistroFiduciarioNotaDevolutivaSituacaoRepository implements RegistroFiduciarioNotaDevolutivaSituacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_nota_devolutiva_situacao::orderBy('dt_cadastro', 'DESC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva_situacao
     * @return registro_fiduciario_nota_devolutiva_situacao|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva_situacao) : ?registro_fiduciario_nota_devolutiva_situacao
    {
        return registro_fiduciario_nota_devolutiva_situacao::find($id_registro_fiduciario_nota_devolutiva_situacao);
    }

}
