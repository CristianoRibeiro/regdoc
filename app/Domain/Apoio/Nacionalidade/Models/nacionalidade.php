<?php

namespace App\Domain\Apoio\Nacionalidade\Models;

use Illuminate\Database\Eloquent\Model;

class nacionalidade extends Model
{
    protected $table = 'nacionalidade';
    protected $primaryKey = 'id_nacionalidade';
    public $timestamps = false;
}
