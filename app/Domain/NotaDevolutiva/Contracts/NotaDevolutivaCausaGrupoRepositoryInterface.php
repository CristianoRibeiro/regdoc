<?php

namespace App\Domain\NotaDevolutiva\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_grupo;

interface NotaDevolutivaCausaGrupoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return Collection
     */
    public function listar(stdClass $args) : Collection;

    /**
     * @param int $id_nota_devolutiva_causa_grupo
     * @return nota_devolutiva_causa_grupo
     */
    public function buscar(int $id_nota_devolutiva_causa_grupo) : ?nota_devolutiva_causa_grupo;

    /**
     * @param int $co_nota_devolutiva_causa_grupo
     * @return nota_devolutiva_causa_grupo
     */
    public function buscar_co(int $co_nota_devolutiva_causa_grupo) : ?nota_devolutiva_causa_grupo;

}
