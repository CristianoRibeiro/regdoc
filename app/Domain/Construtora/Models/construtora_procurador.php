<?php

namespace App\Domain\Construtora\Models;

use Illuminate\Database\Eloquent\Model;

class construtora_procurador extends Model
{
    protected $table = 'construtora_procurador';
    protected $primaryKey = 'id_construtora_procurador';
    public $timestamps = false;

    // Relações
    public function procurador()
    {
        return $this->belongsTo('App\Domain\Procurador\Models\procurador', 'id_procurador');
    }
}
