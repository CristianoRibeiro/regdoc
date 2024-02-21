<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioDajeRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_dajes;

class RegistroFiduciarioDajeRepository implements RegistroFiduciarioDajeRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_dajes
     */
    public function inserir(stdClass $args): registro_fiduciario_dajes
    {
        $registro_daje = new registro_fiduciario_dajes();
        $registro_daje->id_registro_fiduciario = $args->id_registro_fiduciario;
        $registro_daje->no_emissor = $args->no_emissor;
        $registro_daje->nu_serie = $args->nu_serie;
        $registro_daje->nu_daje = $args->nu_daje;
        $registro_daje->va_daje = $args->va_daje;
        $registro_daje->id_usuario_cad = Auth::User()->id_usuario;
        if (!$registro_daje->save()) {
            throw new Exception('Erro ao salvar a daje do registro.');
        }

        return $registro_daje;
    }
}
