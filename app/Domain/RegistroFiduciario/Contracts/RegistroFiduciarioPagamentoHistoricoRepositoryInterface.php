<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_historico;

interface RegistroFiduciarioPagamentoHistoricoRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_pagamento_historico
     * @return registro_fiduciario_pagamento_historico|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_historico) : ?registro_fiduciario_pagamento_historico;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_historico
     */
    public function inserir(stdClass $args) : registro_fiduciario_pagamento_historico;

    /**
     * @param registro_fiduciario_pagamento_historico $registro_fiduciario_pagamento_historico
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_historico
     */
    public function alterar(registro_fiduciario_pagamento_historico $registro_fiduciario_pagamento_historico, stdClass $args) : registro_fiduciario_pagamento_historico;
}
