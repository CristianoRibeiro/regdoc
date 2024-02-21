<?php

namespace App\Domain\Permissao\Models;

use Illuminate\Database\Eloquent\Model;

class perfil_pessoa_permissao extends Model
{
    protected $table = 'perfil_pessoa_permissao';
    protected $primaryKey = 'id_perfil_pessoa_permissao';
    public $timestamps = false;

    public function perfil_pessoa()
    {
        return $this->belongsTo('App\Domain\Permissao\Models\perfil_pessoa', 'id_perfil_pessoa');
    }
    public function permissao()
    {
        return $this->belongsTo('App\Domain\Permissao\Models\permissao', 'id_permissao');
    }

}
