<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioEnderecoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioEnderecoServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_endereco;

class RegistroFiduciarioEnderecoService implements RegistroFiduciarioEnderecoServiceInterface
{
    /**
     * @var RegistroFiduciarioEnderecoRepositoryInterface
     */
    protected $RegistroFiduciarioEnderecoRepositoryInterface;

    /**
     * RegistroFiduciarioEnderecoService constructor.
     * @param RegistroFiduciarioEnderecoRepositoryInterface $RegistroFiduciarioEnderecoRepositoryInterface
     */
    public function __construct(RegistroFiduciarioEnderecoRepositoryInterface $RegistroFiduciarioEnderecoRepositoryInterface)
    {
        $this->RegistroFiduciarioEnderecoRepositoryInterface = $RegistroFiduciarioEnderecoRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_endereco
     */
    public function inserir(stdClass $args): registro_fiduciario_endereco
    {
        return $this->RegistroFiduciarioEnderecoRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_endereco $registro_fiduciario_endereco
     * @param stdClass $args
     * @return registro_fiduciario_endereco
     */
    public function alterar(registro_fiduciario_endereco $registro_fiduciario_endereco, stdClass $args): registro_fiduciario_endereco
    {
        return $this->RegistroFiduciarioEnderecoRepositoryInterface->alterar($registro_fiduciario_endereco, $args);
    }

    /**
     * @param registro_fiduciario_endereco $registro_fiduciario_endereco
     * @return bool
     */
    public function deletar(registro_fiduciario_endereco $registro_fiduciario_endereco) : bool
    {
        return $this->RegistroFiduciarioEnderecoRepositoryInterface->deletar($registro_fiduciario_endereco);
    }
}
