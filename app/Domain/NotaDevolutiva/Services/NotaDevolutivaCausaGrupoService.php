<?php

namespace App\Domain\NotaDevolutiva\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Carbon\Carbon;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_grupo;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaGrupoRepositoryInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaGrupoServiceInterface;


class NotaDevolutivaCausaGrupoService implements NotaDevolutivaCausaGrupoServiceInterface
{
    /**
     * @var NotaDevolutivaCausaGrupoRepositoryInterface;
     */
    protected $NotaDevolutivaCausaGrupoRepositoryInterface;

    /**
     * NotaDevolutivaCausaGrupoService constructor.
     * @param NotaDevolutivaCausaGrupoRepositoryInterface $NotaDevolutivaCausaGrupoRepositoryInterface
     */
    public function __construct(NotaDevolutivaCausaGrupoRepositoryInterface $NotaDevolutivaCausaGrupoRepositoryInterface)
    {
        $this->NotaDevolutivaCausaGrupoRepositoryInterface = $NotaDevolutivaCausaGrupoRepositoryInterface;
       
    }

    /**
     * @param stdClass $args
     * @return Collection
     */
    public function listar(stdClass $args): Collection
    {
        return $this->NotaDevolutivaCausaGrupoRepositoryInterface->listar($args);
    }

    /**
    * @param int $id_nota_devolutiva_causa_grupo
    * @return nota_devolutiva_causa_grupo
    */
    public function buscar(int $id_nota_devolutiva_causa_grupo) : ?nota_devolutiva_causa_grupo
    {
        return $this->NotaDevolutivaCausaGrupoRepositoryInterface->buscar($id_nota_devolutiva_causa_grupo);
    }

    /**
     * @param int $co_nota_devolutiva_causa_grupo
     * @return nota_devolutiva_causa_grupo
     */
    public function buscar_co(int $co_nota_devolutiva_causa_grupo) : ?nota_devolutiva_causa_grupo
    {
        return $this->NotaDevolutivaCausaGrupoRepositoryInterface->buscar_co($co_nota_devolutiva_causa_grupo);
    }

}
