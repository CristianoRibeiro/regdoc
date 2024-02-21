<?php

namespace App\Domain\Permissao\Models;

use Illuminate\Database\Eloquent\Model;

class perfil_pessoa extends Model
{
    protected $table = 'perfil_pessoa';
    protected $primaryKey = 'id_perfil_pessoa';
    public $timestamps = false;

    public function permissoes()
    {
        return $this->belongsToMany('App\Domain\Permissao\Models\permissao', 'perfil_pessoa_permissao', 'id_perfil_pessoa', 'id_permissao');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
}
