<?php
namespace App\Domain\TabelaEmolumento\Services;

use App\Domain\TabelaEmolumento\Contracts\TabelaEmolumentoServiceInterface;
use App\Domain\TabelaEmolumento\Contracts\TabelaEmolumentoRepositoryInterface;

use stdClass;

class TabelaEmolumentoService implements TabelaEmolumentoServiceInterface
{
    /**
     * @var TabelaEmolumentoRepositoryInterface
     */
    protected $TabelaEmolumentoRepositoryInterface;

    /**
     * TabelaEmolumentoService constructor.
     * @param TabelaEmolumentoRepositoryInterface $TabelaEmolumentoRepositoryInterface
     */
    public function __construct(TabelaEmolumentoRepositoryInterface $TabelaEmolumentoRepositoryInterface)
    {
        $this->TabelaEmolumentoRepositoryInterface = $TabelaEmolumentoRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return mixed
     */
    public function calcular_emolumentos(stdClass $args)
    {
        return $this->TabelaEmolumentoRepositoryInterface->calcular_emolumentos($args);
    }
}
