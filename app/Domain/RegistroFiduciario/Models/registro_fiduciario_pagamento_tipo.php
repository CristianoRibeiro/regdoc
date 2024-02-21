<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_pagamento_tipo extends Model
{
    protected $table = 'registro_fiduciario_pagamento_tipo';
    protected $primaryKey = 'id_registro_fiduciario_pagamento_tipo';
    public $timestamps = false;
}
