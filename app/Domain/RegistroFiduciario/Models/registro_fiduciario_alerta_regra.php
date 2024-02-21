<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_alerta_regra extends Model
{
    protected $table = 'registro_fiduciario_alerta_regra';

    protected $primaryKey = 'id_registro_fiduciario_alerta_regra';

    public $timestamps = false;
}