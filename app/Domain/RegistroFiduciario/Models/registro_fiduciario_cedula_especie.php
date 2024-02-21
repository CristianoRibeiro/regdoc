<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_cedula_especie extends Model
{
    protected $table = 'registro_fiduciario_cedula_especie';

    protected $primaryKey = 'id_registro_fiduciario_cedula_especie';

    public $timestamps = false;
}