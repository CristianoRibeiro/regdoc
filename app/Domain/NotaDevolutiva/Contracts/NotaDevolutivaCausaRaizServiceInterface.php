<?php

namespace App\Domain\NotaDevolutiva\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_raiz;

interface NotaDevolutivaCausaRaizServiceInterface
{
    /**
     * @param stdClass $args
     * @return Collection
     */
    public function listar(stdClass $args) : Collection;

    /**
     * @param int $id_nota_devolutiva_causa_raiz
     * @return nota_devolutiva_causa_raiz
     */
    public function buscar(int $id_nota_devolutiva_causa_raiz) : ?nota_devolutiva_causa_raiz;

    /**
     * @param int $co_nota_devolutiva_causa_raiz
     * @return nota_devolutiva_causa_raiz
     */
    public function buscar_co(int $co_nota_devolutiva_causa_raiz) : ?nota_devolutiva_causa_raiz;

}
