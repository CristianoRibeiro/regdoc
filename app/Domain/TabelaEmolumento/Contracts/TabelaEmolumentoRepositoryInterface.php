<?php
namespace App\Domain\TabelaEmolumento\Contracts;

use stdClass;

use App\Domain\TabelaEmolumento\Models\tabela_emolumento;

interface TabelaEmolumentoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return mixed
     */
    public function calcular_emolumentos(stdClass $args);
}
