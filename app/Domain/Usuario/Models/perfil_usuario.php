<?php

namespace App\Domain\Usuario\Models;

use Illuminate\Database\Eloquent\Model;

class perfil_usuario extends Model
{
    protected $table = 'perfil_usuario';

    protected $primaryKey = 'id_perfil_usuario';

    public $timestamps = false;

    public function perfil_usuario_permissoes()
    {
        return $this->hasMany(perfil_usuario_permissao::class, 'id_perfil_usuario');
    }
}