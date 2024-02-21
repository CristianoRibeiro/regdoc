<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_guia;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaServiceInterface;

class RegistroFiduciarioPagamentoGuiaService implements RegistroFiduciarioPagamentoGuiaServiceInterface
{
    /**
     * @var RegistroFiduciarioPagamentoGuiaRepositoryInterface
     */
    protected $RegistroFiduciarioPagamentoGuiaRepositoryInterface;

    /**
     * RegistroFiduciarioPagamentoGuiaService constructor.
     * @param RegistroFiduciarioPagamentoGuiaRepositoryInterface $RegistroFiduciarioPagamentoGuiaRepositoryInterface
     */
    public function __construct(RegistroFiduciarioPagamentoGuiaRepositoryInterface $RegistroFiduciarioPagamentoGuiaRepositoryInterface)
    {
        $this->RegistroFiduciarioPagamentoGuiaRepositoryInterface = $RegistroFiduciarioPagamentoGuiaRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_pagamento_guia
     * @return registro_fiduciario_pagamento_guia|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_guia): ?registro_fiduciario_pagamento_guia
    {
        return $this->RegistroFiduciarioPagamentoGuiaRepositoryInterface->buscar($id_registro_fiduciario_pagamento_guia);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_pagamento_guia|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario_pagamento_guia
    {
        return $this->RegistroFiduciarioPagamentoGuiaRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_guia
     */
    public function inserir(stdClass $args): registro_fiduciario_pagamento_guia
    {
        return $this->RegistroFiduciarioPagamentoGuiaRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_guia
     */
    public function alterar(registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia, stdClass $args) : registro_fiduciario_pagamento_guia
    {
        return $this->RegistroFiduciarioPagamentoGuiaRepositoryInterface->alterar($registro_fiduciario_pagamento_guia, $args);
    }

    /**
     * @param registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia
     * @return registro_fiduciario_pagamento_guia
     */
    public function remover_comprovante(registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia) : registro_fiduciario_pagamento_guia
    {
        return $this->RegistroFiduciarioPagamentoGuiaRepositoryInterface->remover_comprovante($registro_fiduciario_pagamento_guia);
    }
}
