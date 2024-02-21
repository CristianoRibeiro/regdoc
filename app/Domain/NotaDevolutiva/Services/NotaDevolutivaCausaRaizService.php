<?php

namespace App\Domain\NotaDevolutiva\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Carbon\Carbon;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_raiz;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaRaizRepositoryInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaRaizServiceInterface;


class NotaDevolutivaCausaRaizService implements NotaDevolutivaCausaRaizServiceInterface
{
    /**
     * @var NotaDevolutivaCausaRaizRepositoryInterface;
     */
    protected $NotaDevolutivaCausaRaizRepositoryInterface;

    /**
     * NotaDevolutivaCausaRaizService constructor.
     * @param NotaDevolutivaCausaRaizRepositoryInterface $NotaDevolutivaCausaRaizRepositoryInterface
     */
    public function __construct(NotaDevolutivaCausaRaizRepositoryInterface $NotaDevolutivaCausaRaizRepositoryInterface)
    {
        $this->NotaDevolutivaCausaRaizRepositoryInterface = $NotaDevolutivaCausaRaizRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return Collection
     */
    public function listar(stdClass $args): Collection
    {
        return $this->NotaDevolutivaCausaRaizRepositoryInterface->listar($args);
    }

    /**
    * @param int $id_nota_devolutiva_causa_raiz
    * @return nota_devolutiva_causa_raiz
    */
    public function buscar(int $id_nota_devolutiva_causa_raiz) : ?nota_devolutiva_causa_raiz
    {
        return $this->NotaDevolutivaCausaRaizRepositoryInterface->buscar($id_nota_devolutiva_causa_raiz);
    }

    /**
     * @param int $co_nota_devolutiva_causa_raiz
     * @return nota_devolutiva_causa_raiz
     */
    public function buscar_co(int $co_nota_devolutiva_causa_raiz) : ?nota_devolutiva_causa_raiz
    {
        return $this->NotaDevolutivaCausaRaizRepositoryInterface->buscar_co($co_nota_devolutiva_causa_raiz);
    }

}
