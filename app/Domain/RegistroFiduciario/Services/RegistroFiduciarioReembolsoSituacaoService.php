<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_reembolso_situacao;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoSituacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoSituacaoServiceInterface;

class RegistroFiduciarioReembolsoSituacaoService implements RegistroFiduciarioReembolsoSituacaoServiceInterface
{
    /**
     * @var RegistroFiduciarioReembolsoSituacaoRepositoryInterface
     */
    protected $RegistroFiduciarioReembolsoSituacaoRepositoryInterface;

    /**
     * RegistroFiduciarioReembolsoSituacaoService constructor.
     * @param RegistroFiduciarioReembolsoSituacaoRepositoryInterface $RegistroFiduciarioReembolsoSituacaoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioReembolsoSituacaoRepositoryInterface $RegistroFiduciarioReembolsoSituacaoRepositoryInterface)
    {
        $this->RegistroFiduciarioReembolsoSituacaoRepositoryInterface = $RegistroFiduciarioReembolsoSituacaoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioReembolsoSituacaoRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_reembolso_situacao
     * @return registro_fiduciario_reembolso_situacao|null
     */
    public function buscar(int $id_registro_fiduciario_reembolso_situacao): ?registro_fiduciario_reembolso_situacao
    {
        return $this->RegistroFiduciarioReembolsoSituacaoRepositoryInterface->buscar($id_registro_fiduciario_reembolso_situacao);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_reembolso_situacao
     */
    public function inserir(stdClass $args): registro_fiduciario_reembolso_situacao
    {
        return $this->RegistroFiduciarioReembolsoSituacaoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_reembolso_situacao $registro_fiduciario_reembolso_situacao
     * @param stdClass $args
     * @return registro_fiduciario_reembolso_situacao
     */
    public function alterar(registro_fiduciario_reembolso_situacao $registro_fiduciario_reembolso_situacao, stdClass $args) : registro_fiduciario_reembolso_situacao
    {
        return $this->RegistroFiduciarioReembolsoSituacaoRepositoryInterface->alterar($registro_fiduciario_reembolso_situacao, $args);
    }
}
