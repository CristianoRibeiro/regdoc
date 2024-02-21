<?php

namespace App\Domain\Usuario\Models;

use Illuminate\Database\Eloquent\Model;

class usuario_senha extends Model
{
    protected $table = 'usuario_senha';

    protected $primaryKey = 'id_usuario_senha';

    public $timestamps = false;

    // Funções de relacionamento
    public function usuario()
    {
        return $this->belongsTo(usuario::class,'id_usuario');
    }
}