<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_cedula_tipo extends Model
{
    protected $table = 'registro_fiduciario_cedula_tipo';

    protected $primaryKey = 'id_registro_fiduciario_cedula_tipo';

    public $timestamps = false;
}