<?php

namespace App\Domain\NotaDevolutiva\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;
use Helper;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_raiz;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaRaizRepositoryInterface;

class NotaDevolutivaCausaRaizRepository implements NotaDevolutivaCausaRaizRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return Collection
     */
    public function listar(stdClass $args) :Collection
    {
        $nota_devolutiva_causa_raiz = new nota_devolutiva_causa_raiz();
        if($args->id_nota_devolutiva_causa_raiz_grupo) {
            $nota_devolutiva_causa_raiz = $nota_devolutiva_causa_raiz->where('id_nota_devolutiva_causa_grupo', $args->id_nota_devolutiva_causa_raiz_grupo);
        }
        return $nota_devolutiva_causa_raiz->where('in_registro_ativo', 'S')
            ->orderBy('nu_ordem', 'asc')
            ->get();
    }

    /**
     * @param int $id_nota_devolutiva_causa_raiz
     * @return nota_devolutiva_causa_raiz
     */
    public function buscar(int $id_nota_devolutiva_causa_raiz) : ?nota_devolutiva_causa_raiz
    {
        return nota_devolutiva_causa_raiz::find($id_nota_devolutiva_causa_raiz);
    }

    /**
     * @param int $co_nota_devolutiva_causa_raiz
     * @return nota_devolutiva_causa_raiz
     */
    public function buscar_co(int $co_nota_devolutiva_causa_raiz) : ?nota_devolutiva_causa_raiz
    {
        return nota_devolutiva_causa_raiz::where('co_nota_devolutiva_causa_raiz', $co_nota_devolutiva_causa_raiz)->first();
    }
}
