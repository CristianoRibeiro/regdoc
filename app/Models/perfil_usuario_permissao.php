<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class perfil_usuario_permissao extends Model
{
    protected $table = 'perfil_usuario_permissao';

    protected $primaryKey = 'id_perfil_usuario_permissao';

    public $timestamps = false;
}