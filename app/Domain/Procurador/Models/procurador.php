<?php

namespace App\Domain\Procurador\Models;

use Illuminate\Database\Eloquent\Model;

class procurador extends Model
{
    protected $table = 'procurador';
    protected $primaryKey = 'id_procurador';
    public $timestamps = false;
}
