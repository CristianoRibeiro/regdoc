<?php

namespace App\Domain\Procuracao\Models;

use Illuminate\Database\Eloquent\Model;

class procuracao_tipo_instrumento extends Model
{
    protected $table = 'procuracao_tipo_instrumento';
    protected $primaryKey = 'id_procuracao_tipo_instrumento';
    public $timestamps = false;
}
