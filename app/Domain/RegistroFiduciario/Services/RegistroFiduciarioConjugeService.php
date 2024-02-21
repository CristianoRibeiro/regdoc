<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioConjugeRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioConjugeServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_conjuge;

class RegistroFiduciarioConjugeService implements RegistroFiduciarioConjugeServiceInterface
{
    /**
     * @var RegistroFiduciarioConjugeRepositoryInterface
     */
    protected $RegistroFiduciarioConjugueRepositoryInterface;

    /**
     * RegistroFiduciarioConjugeService constructor.
     * @param RegistroFiduciarioConjugeRepositoryInterface $RegistroFiduciarioConjugueRepositoryInterface
     */
    public function __construct(RegistroFiduciarioConjugeRepositoryInterface $RegistroFiduciarioConjugueRepositoryInterface)
    {
        $this->RegistroFiduciarioConjugueRepositoryInterface = $RegistroFiduciarioConjugueRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_conjuge
     */
    public function inserir(stdClass $args): registro_fiduciario_conjuge
    {
        $this->RegistroFiduciarioConjugueRepositoryInterface->inserir($args);
    }
}
