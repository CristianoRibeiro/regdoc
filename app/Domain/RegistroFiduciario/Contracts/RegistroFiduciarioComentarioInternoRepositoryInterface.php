<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use stdClass;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_comentario_interno;

interface RegistroFiduciarioComentarioInternoRepositoryInterface
{
    public function buscar(int $idRegistroFiduciarioComentario) : ?registro_fiduciario_comentario_interno;

    public function buscar_uuid(string $uuid): ?registro_fiduciario_comentario_interno;

    public function inserir(stdClass $args) : registro_fiduciario_comentario_interno;

    public function alterar(registro_fiduciario_comentario_interno $registro_fiduciario_comentario, stdClass $args) : registro_fiduciario_comentario_interno;
}