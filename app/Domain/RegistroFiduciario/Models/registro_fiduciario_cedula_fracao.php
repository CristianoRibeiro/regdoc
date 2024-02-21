<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_cedula_fracao extends Model
{
    protected $table = 'registro_fiduciario_cedula_fracao';

    protected $primaryKey = 'id_registro_fiduciario_cedula_fracao';

    public $timestamps = false;
}