<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_tipo;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoTipoServiceInterface;

class RegistroFiduciarioPagamentoTipoService implements RegistroFiduciarioPagamentoTipoServiceInterface
{
    /**
     * @var RegistroFiduciarioPagamentoTipoRepositoryInterface
     */
    protected $RegistroFiduciarioPagamentoTipoRepositoryInterface;

    /**
     * RegistroFiduciarioPagamentoTipoService constructor.
     * @param RegistroFiduciarioPagamentoTipoRepositoryInterface $RegistroFiduciarioPagamentoTipoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioPagamentoTipoRepositoryInterface $RegistroFiduciarioPagamentoTipoRepositoryInterface)
    {
        $this->RegistroFiduciarioPagamentoTipoRepositoryInterface = $RegistroFiduciarioPagamentoTipoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return $this->RegistroFiduciarioPagamentoTipoRepositoryInterface->listar();
    }

    /**
     * @param int $id_registro_fiduciario_pagamento_tipo
     * @return registro_fiduciario_pagamento_tipo|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_tipo): ?registro_fiduciario_pagamento_tipo
    {
        return $this->RegistroFiduciarioPagamentoTipoServiceInterface->buscar($id_registro_fiduciario_pagamento_tipo);
    }


    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_tipo
     */
    public function inserir(stdClass $args): registro_fiduciario_pagamento_tipo
    {
        return $this->RegistroFiduciarioPagamentoTipoServiceInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_pagamento_tipo $registro_fiduciario_pagamento_tipo
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_tipo
     */
    public function alterar(registro_fiduciario_pagamento_tipo $registro_fiduciario_pagamento_tipo, stdClass $args) : registro_fiduciario_pagamento_tipo
    {
        return $this->RegistroFiduciarioPagamentoRepositoryInterface->alterar($registro_fiduciario_pagamento_tipo, $args);
    }
}
