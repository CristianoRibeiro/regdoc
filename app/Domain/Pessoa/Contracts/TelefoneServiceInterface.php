<?php

namespace App\Domain\Pessoa\Contracts;

use App\Domain\Pessoa\Models\telefone;
use stdClass;

interface TelefoneServiceInterface
{
    /**
     * @param stdClass $args
     * @return telefone
     */
    public function insere (stdClass $args) : telefone;
}