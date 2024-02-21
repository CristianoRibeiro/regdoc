<?php

namespace App\Domain\NotaDevolutiva\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;
use Helper;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_classificacao;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaClassificacaoRepositoryInterface;

class NotaDevolutivaCausaClassificacaoRepository implements NotaDevolutivaCausaClassificacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        $nota_devolutiva_causa_classificacao = new nota_devolutiva_causa_classificacao();
        return $nota_devolutiva_causa_classificacao->where('in_registro_ativo', 'S')
            ->orderBy('nu_ordem', 'asc')
            ->get();
    }

    /**
     * @param int $id_nota_devolutiva_causa_classificacao
     * @return nota_devolutiva_causa_classificacao
     */
    public function buscar(int $id_nota_devolutiva_causa_classificacao) : ?nota_devolutiva_causa_classificacao
    {
        return nota_devolutiva_causa_classificacao::find($id_nota_devolutiva_causa_classificacao);
    }

    /**
     * @param int $co_nota_devolutiva_causa_classificacao
     * @return nota_devolutiva_causa_classificacao
     */
    public function buscar_co(int $co_nota_devolutiva_causa_classificacao) : ?nota_devolutiva_causa_classificacao
    {
        return nota_devolutiva_causa_classificacao::where('co_nota_devolutiva_causa_classificacao', $co_nota_devolutiva_causa_classificacao)->first();
    }
}
