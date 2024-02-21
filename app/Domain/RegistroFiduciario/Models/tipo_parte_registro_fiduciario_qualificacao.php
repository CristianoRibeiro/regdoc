<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class tipo_parte_registro_fiduciario_qualificacao extends Model
{
    protected $table = 'tipo_parte_registro_fiduciario_qualificacao';
    protected $primaryKey = 'id_tipo_parte_registro_fiduciario_qualificacao';
    public $timestamps = false;
}
