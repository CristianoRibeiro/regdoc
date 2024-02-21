<?php

namespace App\Domain\NotaDevolutiva\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_cumprimento;

interface NotaDevolutivaCumprimentoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_nota_devolutiva_cumprimento
     * @return nota_devolutiva_cumprimento
     */
    public function buscar(int $id_nota_devolutiva_cumprimento) : ?nota_devolutiva_cumprimento;

    /**
     * @param int $co_nota_devolutiva_cumprimento
     * @return nota_devolutiva_cumprimento
     */
    public function buscar_co(int $co_nota_devolutiva_cumprimento) : ?nota_devolutiva_cumprimento;

}
