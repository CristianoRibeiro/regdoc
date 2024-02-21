<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class tipo_parte_registro_fiduciario extends Model
{
    protected $table = 'tipo_parte_registro_fiduciario';
    protected $primaryKey = 'id_tipo_parte_registro_fiduciario';
    public $timestamps = false;

    public function tipo_parte_registro_fiduciario_qualificacao()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\tipo_parte_registro_fiduciario_qualificacao', 'id_tipo_parte_registro_fiduciario');
    }
}
