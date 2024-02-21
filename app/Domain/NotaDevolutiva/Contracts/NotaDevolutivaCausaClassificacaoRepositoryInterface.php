<?php

namespace App\Domain\NotaDevolutiva\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_classificacao;

interface NotaDevolutivaCausaClassificacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_nota_devolutiva_causa_classificacao
     * @return nota_devolutiva_causa_classificacao
     */
    public function buscar(int $id_nota_devolutiva_causa_classificacao) : ?nota_devolutiva_causa_classificacao;

    /**
     * @param int $co_nota_devolutiva_causa_classificacao
     * @return nota_devolutiva_causa_classificacao
     */
    public function buscar_co(int $co_nota_devolutiva_causa_classificacao) : ?nota_devolutiva_causa_classificacao;

}
