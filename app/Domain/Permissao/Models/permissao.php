<?php

namespace App\Domain\Permissao\Models;

use Illuminate\Database\Eloquent\Model;

class permissao extends Model
{
    protected $table = 'permissao';
    protected $primaryKey = 'id_permissao';
    public $timestamps = false;
}
