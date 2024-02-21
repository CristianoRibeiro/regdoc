<?php

namespace App\Domain\Configuracao\Models;

use Illuminate\Database\Eloquent\Model;

class configuracao extends Model
{
    protected $table = 'configuracao';
    protected $primaryKey = 'id_configuracao';
    public $timestamps = false;

}
