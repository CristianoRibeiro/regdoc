<?php

namespace App\Domain\Pessoa\Services;

use App\Domain\Pessoa\Contracts\TelefoneRepositoryInterface;
use App\Domain\Pessoa\Contracts\TelefoneServiceInterface;
use App\Domain\Pessoa\Models\telefone;
use stdClass;

class TelefoneService implements TelefoneServiceInterface
{
    /**
     * @var TelefoneRepositoryInterface
     */
    protected $TelefoneRepositoryInterface;

    /**
     * TelefoneService constructor.
     * @param TelefoneRepositoryInterface $TelefoneRepositoryInterface
     */
    public function __construct(TelefoneRepositoryInterface $TelefoneRepositoryInterface)
    {
        $this->TelefoneRepositoryInterface = $TelefoneRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return telefone
     */
    public function insere(stdClass $args): telefone
    {
        return $this->TelefoneRepositoryInterface->insere($args);
    }
}