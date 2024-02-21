<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaServiceInterface;

class RegistroFiduciarioNotaDevolutivaService implements RegistroFiduciarioNotaDevolutivaServiceInterface
{
    /**
     * @var RegistroFiduciarioNotaDevolutivaRepositoryInterface
     */
    protected $RegistroFiduciarioNotaDevolutivaRepositoryInterface;

    /**
     * RegistroFiduciarioNotaDevolutivaService constructor.
     * @param RegistroFiduciarioNotaDevolutivaRepositoryInterface $RegistroFiduciarioNotaDevolutivaRepositoryInterface
     */
    public function __construct(RegistroFiduciarioNotaDevolutivaRepositoryInterface $RegistroFiduciarioNotaDevolutivaRepositoryInterface)
    {
        $this->RegistroFiduciarioNotaDevolutivaRepositoryInterface = $RegistroFiduciarioNotaDevolutivaRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioNotaDevolutivaRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_nota_devolutiva
     * @return registro_fiduciario_nota_devolutiva|null
     */
    public function buscar(int $id_registro_fiduciario_nota_devolutiva): ?registro_fiduciario_nota_devolutiva
    {
        return $this->RegistroFiduciarioNotaDevolutivaRepositoryInterface->buscar($id_registro_fiduciario_nota_devolutiva);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_nota_devolutiva|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario_nota_devolutiva
    {
        return $this->RegistroFiduciarioNotaDevolutivaRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva
     */
    public function inserir(stdClass $args): registro_fiduciario_nota_devolutiva
    {
        return $this->RegistroFiduciarioNotaDevolutivaRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_nota_devolutiva $registro_fiduciario_nota_devolutiva
     * @param stdClass $args
     * @return registro_fiduciario_nota_devolutiva
     */
    public function alterar(registro_fiduciario_nota_devolutiva $registro_fiduciario_nota_devolutiva, stdClass $args) : registro_fiduciario_nota_devolutiva
    {
        return $this->RegistroFiduciarioNotaDevolutivaRepositoryInterface->alterar($registro_fiduciario_nota_devolutiva, $args);
    }

    public function alterarSituacaoPorFiduciario(registro_fiduciario $registro, int $idSituacao, $id_registro_fiduciario_nota_devolutiva): bool
    {
        return $this->RegistroFiduciarioNotaDevolutivaRepositoryInterface->alterarSituacaoPorFiduciario($registro, $idSituacao, $id_registro_fiduciario_nota_devolutiva);
    }
}
