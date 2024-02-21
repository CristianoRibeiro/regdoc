<?php

namespace App\Domain\Apoio\EstadoCivil\Models;

use Illuminate\Database\Eloquent\Model;

class estado_civil extends Model
{
    protected $table = 'estado_civil';
    protected $primaryKey = 'id_estado_civil';
    public $timestamps = false;
}
