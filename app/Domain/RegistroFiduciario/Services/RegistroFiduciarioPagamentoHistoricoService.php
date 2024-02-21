<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_historico;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoHistoricoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoHistoricoServiceInterface;

class RegistroFiduciarioPagamentoHistoricoService implements RegistroFiduciarioPagamentoHistoricoServiceInterface
{
    /**
     * @var RegistroFiduciarioPagamentoHistoricoRepositoryInterface
     */
    protected $RegistroFiduciarioPagamentoHistoricoRepositoryInterface;

    /**
     * RegistroFiduciarioPagamentoHistoricoService constructor.
     * @param RegistroFiduciarioPagamentoHistoricoRepositoryInterface $RegistroFiduciarioPagamentoHistoricoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioPagamentoHistoricoRepositoryInterface $RegistroFiduciarioPagamentoHistoricoRepositoryInterface)
    {
        $this->RegistroFiduciarioPagamentoHistoricoRepositoryInterface = $RegistroFiduciarioPagamentoHistoricoRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_pagamento_historico
     * @return registro_fiduciario_pagamento_historico|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_historico): ?registro_fiduciario_pagamento_historico
    {
        return $this->RegistroFiduciarioPagamentoHistoricoRepositoryInterface->buscar($id_registro_fiduciario_pagamento_historico);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_historico
     */
    public function inserir(stdClass $args): registro_fiduciario_pagamento_historico
    {
        return $this->RegistroFiduciarioPagamentoHistoricoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_pagamento_historico $registro_fiduciario_pagamento_historico
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_historico
     */
    public function alterar(registro_fiduciario_pagamento_historico $registro_fiduciario_pagamento_historico, stdClass $args) : registro_fiduciario_pagamento_historico
    {
        return $this->RegistroFiduciarioPagamentoHistoricoRepositoryInterface->alterar($registro_fiduciario_pagamento_historico, $args);
    }
}
