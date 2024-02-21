<?php

namespace App\Domain\Pessoa\Models;

use Illuminate\Database\Eloquent\Model;

class pessoa_perfil_pessoa extends Model
{
    protected $table = 'pessoa_perfil_pessoa';
    protected $primaryKey = 'id_pessoa_perfil_pessoa';
    public $timestamps = false;

    public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
    public function perfil_pessoa()
    {
        return $this->belongsTo('App\Domain\Permissao\Models\perfil_pessoa', 'id_perfil_pessoa');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }

}
