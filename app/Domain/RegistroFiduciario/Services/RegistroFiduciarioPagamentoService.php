<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoServiceInterface;

class RegistroFiduciarioPagamentoService implements RegistroFiduciarioPagamentoServiceInterface
{
    /**
     * @var RegistroFiduciarioPagamentoRepositoryInterface
     */
    protected $RegistroFiduciarioPagamentoRepositoryInterface;

    /**
     * RegistroFiduciarioPagamentoService constructor.
     * @param RegistroFiduciarioPagamentoRepositoryInterface $RegistroFiduciarioPagamentoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioPagamentoRepositoryInterface $RegistroFiduciarioPagamentoRepositoryInterface)
    {
        $this->RegistroFiduciarioPagamentoRepositoryInterface = $RegistroFiduciarioPagamentoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioPagamentoRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_pagamento
     * @return registro_fiduciario_pagamento|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento): ?registro_fiduciario_pagamento
    {
        return $this->RegistroFiduciarioPagamentoRepositoryInterface->buscar($id_registro_fiduciario_pagamento);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_pagamento|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario_pagamento
    {
        return $this->RegistroFiduciarioPagamentoRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento
     */
    public function inserir(stdClass $args): registro_fiduciario_pagamento
    {
        return $this->RegistroFiduciarioPagamentoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_pagamento $registro_fiduciario_pagamento
     * @param stdClass $args
     * @return registro_fiduciario_pagamento
     */
    public function alterar(registro_fiduciario_pagamento $registro_fiduciario_pagamento, stdClass $args) : registro_fiduciario_pagamento
    {
        return $this->RegistroFiduciarioPagamentoRepositoryInterface->alterar($registro_fiduciario_pagamento, $args);
    }
}
