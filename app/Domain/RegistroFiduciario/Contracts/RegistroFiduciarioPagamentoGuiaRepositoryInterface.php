<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_guia;

interface RegistroFiduciarioPagamentoGuiaRepositoryInterface
{
    /**
     * @param int $id_registro_fiduciario_pagamento_guia
     * @return registro_fiduciario_pagamento_guia|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_guia) : ?registro_fiduciario_pagamento_guia;

    /**
     * @param string $uuid
     * @return registro_fiduciario_pagamento_guia|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario_pagamento_guia;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_guia
     */
    public function inserir(stdClass $args) : registro_fiduciario_pagamento_guia;

    /**
     * @param registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_guia
     */
    public function alterar(registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia, stdClass $args) : registro_fiduciario_pagamento_guia;

    /**
     * @param registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia
     * @return registro_fiduciario_pagamento_guia
     */
    public function remover_comprovante(registro_fiduciario_pagamento_guia $registro_fiduciario_pagamento_guia) : registro_fiduciario_pagamento_guia;
}
