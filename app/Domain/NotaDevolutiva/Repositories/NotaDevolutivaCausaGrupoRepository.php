<?php

namespace App\Domain\NotaDevolutiva\Repositories;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Exception;
use Auth;
use Helper;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_grupo;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaGrupoRepositoryInterface;

class NotaDevolutivaCausaGrupoRepository implements NotaDevolutivaCausaGrupoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return Collection
     */
    public function listar(stdClass $args) : Collection
    {
        $nota_devolutiva_causa_grupo = new nota_devolutiva_causa_grupo();
        if($args->id_causa_raiz_classificacao) {
            $nota_devolutiva_causa_grupo = $nota_devolutiva_causa_grupo->where('id_nota_devolutiva_causa_classificacao', $args->id_causa_raiz_classificacao);
        }
        return $nota_devolutiva_causa_grupo->where('in_registro_ativo', 'S')
            ->orderBy('nu_ordem', 'asc')
            ->get();
    }

    /**
     * @param int $id_nota_devolutiva_causa_grupo
     * @return nota_devolutiva_causa_grupo
     */
    public function buscar(int $id_nota_devolutiva_causa_grupo) : ?nota_devolutiva_causa_grupo
    {
        return nota_devolutiva_causa_grupo::find($id_nota_devolutiva_causa_grupo);
    }

    /**
     * @param int $co_nota_devolutiva_causa_grupo
     * @return nota_devolutiva_causa_grupo
     */
    public function buscar_co(int $co_nota_devolutiva_causa_grupo) : ?nota_devolutiva_causa_grupo
    {
        return nota_devolutiva_causa_grupo::where('co_nota_devolutiva_causa_grupo', $co_nota_devolutiva_causa_grupo)->first();
    }
}
