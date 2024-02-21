<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_pagamento_historico extends Model
{
    protected $table = 'registro_fiduciario_pagamento_historico';
    protected $primaryKey = 'id_registro_fiduciario_pagamento_historico';
    public $timestamps = false;
}
