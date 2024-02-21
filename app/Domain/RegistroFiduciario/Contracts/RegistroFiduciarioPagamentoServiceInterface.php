<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use  App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento;

interface RegistroFiduciarioPagamentoServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_pagamento
     * @return registro_fiduciario_pagamento|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento) : ?registro_fiduciario_pagamento;

    /**
     * @param string $uuid
     * @return registro_fiduciario_pagamento|null
     */
    public function buscar_uuid(string $uuid) : ?registro_fiduciario_pagamento;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento
     */
    public function inserir(stdClass $args) : registro_fiduciario_pagamento;

    /**
     * @param registro_fiduciario_pagamento $registro_fiduciario_pagamento
     * @param stdClass $args
     * @return registro_fiduciario_pagamento
     */
    public function alterar(registro_fiduciario_pagamento $registro_fiduciario_pagamento, stdClass $args) : registro_fiduciario_pagamento;
}
