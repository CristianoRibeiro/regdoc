<?php

namespace App\Domain\NotaDevolutiva\Services;

use Illuminate\Database\Eloquent\Collection;

use stdClass;
use Carbon\Carbon;

use App\Domain\NotaDevolutiva\Models\nota_devolutiva_cumprimento;

use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCumprimentoRepositoryInterface;
use App\Domain\NotaDevolutiva\Contracts\NotaDevolutivaCumprimentoServiceInterface;


class NotaDevolutivaCumprimentoService implements NotaDevolutivaCumprimentoServiceInterface
{
    /**
     * @var NotaDevolutivaCumprimentoRepositoryInterface;
     */
    protected $NotaDevolutivaCumprimentoRepositoryInterface;

    /**
     * NotaDevolutivaCumprimentoService constructor.
     * @param NotaDevolutivaCumprimentoRepositoryInterface $NotaDevolutivaCumprimentoRepositoryInterface
     */
    public function __construct(NotaDevolutivaCumprimentoRepositoryInterface $NotaDevolutivaCumprimentoRepositoryInterface)
    {
        $this->NotaDevolutivaCumprimentoRepositoryInterface = $NotaDevolutivaCumprimentoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->NotaDevolutivaCumprimentoRepositoryInterface->listar();
    }

    /**
    * @param int $id_nota_devolutiva_cumprimento
    * @return nota_devolutiva_cumprimento
    */
    public function buscar(int $id_nota_devolutiva_cumprimento) : ?nota_devolutiva_cumprimento
    {
        return $this->NotaDevolutivaCumprimentoRepositoryInterface->buscar($id_nota_devolutiva_cumprimento);
    }

    /**
     * @param int $co_nota_devolutiva_cumprimento
     * @return nota_devolutiva_cumprimento
     */
    public function buscar_co(int $co_nota_devolutiva_cumprimento) : ?nota_devolutiva_cumprimento
    {
        return $this->NotaDevolutivaCumprimentoRepositoryInterface->buscar_co($nu_cpf_cnpj);
    }

}
