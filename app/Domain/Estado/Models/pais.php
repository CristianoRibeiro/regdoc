<?php

namespace App\Domain\Estado\Models;

use Illuminate\Database\Eloquent\Model;

class pais extends Model
{
    protected $table = 'pais';

    protected $primaryKey = 'id_pais';

    public $timestamps = false;

    protected $guarded  = array();
}