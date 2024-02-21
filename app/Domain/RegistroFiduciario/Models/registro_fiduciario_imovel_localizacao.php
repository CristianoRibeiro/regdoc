<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_imovel_localizacao extends Model
{
    protected $table = 'registro_fiduciario_imovel_localizacao';

    protected $primaryKey = 'id_registro_fiduciario_imovel_localizacao';

    public $timestamps = false;
}