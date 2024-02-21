<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_pagamento_situacao extends Model
{
    protected $table = 'registro_fiduciario_pagamento_situacao';
    protected $primaryKey = 'id_registro_fiduciario_pagamento_situacao';
    public $timestamps = false;
}
