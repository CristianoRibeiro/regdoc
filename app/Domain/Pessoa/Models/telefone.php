<?php

namespace App\Domain\Pessoa\Models;

use Illuminate\Database\Eloquent\Model;

class telefone extends Model
{
    protected $table = 'telefone';

    protected $primaryKey = 'id_telefone';

    public $timestamps = false;

    // Funções de relacionamento
    public function tipo_telefone() {
        return $this->belongsTo(tipo_telefone::class,'id_tipo_telefone');
    }
}