<?php

namespace App\Domain\Pessoa\Models;

use Illuminate\Database\Eloquent\Model;

class tipo_telefone extends Model
{
    protected $table = 'tipo_telefone';

    protected $primaryKey = 'id_tipo_telefone';

    public $timestamps = false;

    protected $guarded  = array();

    public function telefone() {
        return $this->hasOne(telefone::class,'id_telefone');
    }
}