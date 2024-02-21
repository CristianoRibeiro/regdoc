<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_comentario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

interface RegistroFiduciarioComentarioServiceInterface
{
    /**
     * @param int $id_registro_fiduciario_comentario
     * @return registro_fiduciario_comentario|null
     */
    public function buscar(int $id_registro_fiduciario_comentario) : ?registro_fiduciario_comentario;

    /**
     * @param string $uuid
     * @return registro_fiduciario_comentario|null
     */
    public function buscar_uuid(string $uuid): ?registro_fiduciario_comentario;
    
    /**
     * @param stdClass $args
     * @return registro_fiduciario_comentario
     */
    public function inserir(stdClass $args) : registro_fiduciario_comentario;

    /**
     * @param registro_fiduciario_comentario $registro_fiduciario_comentario
     * @param stdClass $args
     * @return registro_fiduciario_comentario
     */
    public function alterar(registro_fiduciario_comentario $registro_fiduciario_comentario, stdClass $args) : registro_fiduciario_comentario;

    public function buscar_comentarios_com_filtros(registro_fiduciario $registro_fiduciario, array $filtros);
}
