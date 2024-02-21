<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioImovelServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel;

class RegistroFiduciarioImovelService implements RegistroFiduciarioImovelServiceInterface
{
    /**
     * @var RegistroFiduciarioImovelRepositoryInterface
     */
    protected $RegistroFiduciarioImovelRepositoryInterface;

    /**
     * RegistroFiduciarioImovelService constructor.
     * @param RegistroFiduciarioImovelRepositoryInterface $RegistroFiduciarioImovelRepositoryInterface
     */
    public function __construct(RegistroFiduciarioImovelRepositoryInterface $RegistroFiduciarioImovelRepositoryInterface)
    {
        $this->RegistroFiduciarioImovelRepositoryInterface = $RegistroFiduciarioImovelRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_imovel
     * @param bool $retornar_endereco
     * @return registro_fiduciario_imovel|null
     */
    public function buscar(int $id_registro_fiduciario_imovel, bool $retornar_endereco = false): ?registro_fiduciario_imovel
    {
        return $this->RegistroFiduciarioImovelRepositoryInterface->buscar($id_registro_fiduciario_imovel, $retornar_endereco);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_imovel
     */
    public function inserir(stdClass $args): registro_fiduciario_imovel
    {
        return $this->RegistroFiduciarioImovelRepositoryInterface->inserir($args);
    }

    /**
     * @param registro_fiduciario_imovel $registro_fiduciario_imovel
     * @param stdClass $args
     * @return registro_fiduciario_imovel
     */
    public function alterar(registro_fiduciario_imovel $registro_fiduciario_imovel, stdClass $args) : registro_fiduciario_imovel
    {
        return $this->RegistroFiduciarioImovelRepositoryInterface->alterar($registro_fiduciario_imovel, $args);
    }

    /**
     * @param registro_fiduciario_imovel $registro_fiduciario_imovel
     * @return bool
     */
    public function deletar(registro_fiduciario_imovel $registro_fiduciario_imovel) : bool
    {
        return $this->RegistroFiduciarioImovelRepositoryInterface->deletar($registro_fiduciario_imovel);
    }
}
