<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usuario_2fa_email extends Model
{
    protected $table = 'usuario_2fa_email';

    protected $primaryKey = 'id_usuario_2fa_email';

    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(usuario::class, 'id_usuario');
    }
}