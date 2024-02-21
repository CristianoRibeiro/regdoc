<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class banco extends Model
{
    protected $table = 'banco';

    protected $primaryKey = 'id_banco';

    public $timestamps = false;
}