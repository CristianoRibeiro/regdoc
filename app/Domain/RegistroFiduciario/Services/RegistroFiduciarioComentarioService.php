<?php

namespace App\Domain\RegistroFiduciario\Services;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_comentario;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioServiceInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class RegistroFiduciarioComentarioService implements RegistroFiduciarioComentarioServiceInterface
{
    /**
     * @var RegistroFiduciarioComentarioRepositoryInterface
     */
    protected $RegistroFiduciarioComentarioRepositoryInterface;

    /**
     * RegistroFiduciarioComentarioService constructor.
     * @param RegistroFiduciarioComentarioRepositoryInterface $RegistroFiduciarioComentarioRepositoryInterface
     */
    public function __construct(RegistroFiduciarioComentarioRepositoryInterface $RegistroFiduciarioComentarioRepositoryInterface)
    {
        return $this->RegistroFiduciarioComentarioRepositoryInterface = $RegistroFiduciarioComentarioRepositoryInterface;
    }

    /**
     * @param int $id_registro_fiduciario_comentario
     * @return registro_fiduciario_comentario|null
     */
    public function buscar(int $id_registro_fiduciario_comentario): ?registro_fiduciario_comentario
    {
        return $this->RegistroFiduciarioComentarioRepositoryInterface->buscar($id_registro_fiduciario_comentario);
    }

    /**
     * @param string $uuid
     * @return registro_fiduciario_comentario|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario_comentario
    {
        return $this->RegistroFiduciarioComentarioRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return registro_fiduciario_comentario
     */
    public function inserir(stdClass $args): registro_fiduciario_comentario
    {
        return $this->RegistroFiduciarioComentarioRepositoryInterface->inserir($args);
    }

    public function alterar(registro_fiduciario_comentario $registro_fiduciario_comentario, stdClass $args): registro_fiduciario_comentario
    {
        return $this->RegistroFiduciarioComentarioRepositoryInterface->alterar($registro_fiduciario_comentario, $args);
    }

    public function buscar_comentarios_com_filtros(registro_fiduciario $registro_fiduciario, array $filtros)
    {
        return $this->RegistroFiduciarioComentarioRepositoryInterface->buscar_comentarios_com_filtros($registro_fiduciario, $filtros);
    }
}
