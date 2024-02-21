<?php

namespace App\Domain\NotaDevolutiva\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;
use Helper;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_cumprimento;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCumprimentoRepositoryInterface;

class NotaDevolutivaCumprimentoRepository implements NotaDevolutivaCumprimentoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        $nota_devolutiva_cumprimento = new nota_devolutiva_cumprimento();
        return $nota_devolutiva_cumprimento->where('in_registro_ativo', 'S')
            ->orderBy('nu_ordem', 'asc')
            ->get();
    }

    /**
     * @param int $id_nota_devolutiva_cumprimento
     * @return nota_devolutiva_cumprimento
     */
    public function buscar(int $id_nota_devolutiva_cumprimento) : ?nota_devolutiva_cumprimento
    {
        return nota_devolutiva_cumprimento::find($id_nota_devolutiva_cumprimento);
    }

    /**
     * @param int $co_nota_devolutiva_cumprimento
     * @return nota_devolutiva_cumprimento
     */
    public function buscar_co(int $co_nota_devolutiva_cumprimento) : ?nota_devolutiva_cumprimento
    {
        return nota_devolutiva_cumprimento::where('co_nota_devolutiva_cumprimento', $co_nota_devolutiva_cumprimento)->first();
    }
}
