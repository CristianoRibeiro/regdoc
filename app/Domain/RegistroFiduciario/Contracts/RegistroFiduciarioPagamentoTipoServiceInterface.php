<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_tipo;

interface RegistroFiduciarioPagamentoTipoServiceInterface
{
    
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_pagamento_tipo
     * @return registro_fiduciario_pagamento_tipo|null
     */
    public function buscar(int $id_registro_fiduciario_pagamento_tipo) : ?registro_fiduciario_pagamento_tipo;

    /**
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_tipo
     */
    public function inserir(stdClass $args) : registro_fiduciario_pagamento_tipo;

     /**
     * @param registro_fiduciario_pagamento_tipo $registro_fiduciario_pagamento_tipo
     * @param stdClass $args
     * @return registro_fiduciario_pagamento_tipo
     */
    public function alterar(registro_fiduciario_pagamento_tipo $registro_fiduciario_pagamento_tipo, stdClass $args) : registro_fiduciario_pagamento_tipo;
}
