<?php

namespace App\Domain\Construtora\Models;

use Illuminate\Database\Eloquent\Model;

class empreendimento extends Model
{
    protected $table = 'empreendimento';
    protected $primaryKey = 'id_empreendimento';
    public $timestamps = false;

    // Relações
    public function construtora()
    {
        return $this->belongsTo('App\Domain\Construtora\Models\construtora', 'id_construtora');
    }
}
