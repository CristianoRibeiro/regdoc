<?php

namespace App\Domain\NotaDevolutiva\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Carbon\Carbon;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_classificacao;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaClassificacaoRepositoryInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCausaClassificacaoServiceInterface;


class NotaDevolutivaCausaClassificacaoService implements NotaDevolutivaCausaClassificacaoServiceInterface
{
    /**
     * @var NotaDevolutivaCausaClassificacaoRepositoryInterface;
     */
    protected $NotaDevolutivaCausaClassificacaoRepositoryInterface;

    /**
     * NotaDevolutivaCausaClassificacaoService constructor.
     * @param NotaDevolutivaCausaClassificacaoRepositoryInterface $NotaDevolutivaCausaClassificacaoRepositoryInterface
     */
    public function __construct(NotaDevolutivaCausaClassificacaoRepositoryInterface $NotaDevolutivaCausaClassificacaoRepositoryInterface)
    {
        $this->NotaDevolutivaCausaClassificacaoRepositoryInterface = $NotaDevolutivaCausaClassificacaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->NotaDevolutivaCausaClassificacaoRepositoryInterface->listar();
    }

    /**
    * @param int $id_nota_devolutiva_causa_classificacao
    * @return nota_devolutiva_causa_classificacao
    */
    public function buscar(int $id_nota_devolutiva_causa_classificacao) : ?nota_devolutiva_causa_classificacao
    {
        return $this->NotaDevolutivaCausaClassificacaoRepositoryInterface->buscar($id_nota_devolutiva_causa_classificacao);
    }

    /**
     * @param int $co_nota_devolutiva_causa_classificacao
     * @return nota_devolutiva_causa_classificacao
     */
    public function buscar_co(int $co_nota_devolutiva_causa_classificacao) : ?nota_devolutiva_causa_classificacao
    {
        return $this->NotaDevolutivaCausaClassificacaoRepositoryInterface->buscar_co($co_nota_devolutiva_causa_classificacao);
    }

}
