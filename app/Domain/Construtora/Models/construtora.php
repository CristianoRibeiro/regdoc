<?php

namespace App\Domain\Construtora\Models;

use Illuminate\Database\Eloquent\Model;

class construtora extends Model
{
    protected $table = 'construtora';
    protected $primaryKey = 'id_construtora';
    public $timestamps = false;

    // Relações
    public function construtora_procurador()
    {
        return $this->hasMany('App\Domain\Construtora\Models\construtora_procurador', 'id_construtora');
    }
    public function empreendimentos()
    {
        return $this->hasMany('App\Domain\Construtora\Models\empreendimento', 'id_construtora');
    }
    public function cidade()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade_endereco');
    }
}
