<?php

namespace App\Domain\Usuario\Models;

use Illuminate\Database\Eloquent\Model;

class cargo extends Model
{
    protected $table = 'cargo';

    protected $primaryKey = 'id_cargo';

    public $timestamps = false;

    // Funções de relacionamento
    public function usuario() {
        return $this->belongsTo(usuario::class,'id_cargo');
    }
}