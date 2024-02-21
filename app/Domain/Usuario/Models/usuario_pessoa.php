<?php

namespace App\Domain\Usuario\Models;

use App\Domain\Pessoa\Models\pessoa;
use Illuminate\Database\Eloquent\Model;

class usuario_pessoa extends Model
{
    protected $table = 'usuario_pessoa';

    protected $primaryKey = 'id_usuario_pessoa';

    public $timestamps = false;

    // Funções de relacionamento
    public function usuario()
    {
        return $this->belongsTo(usuario::class,'id_usuario');
    }
    public function pessoa()
    {
        return $this->belongsTo(pessoa::class,'id_pessoa');
    }

    // Funções especiais
    public function insere($args)
    {
        $this->id_pessoa = $args['id_pessoa'];
        $this->id_usuario = $args['id_usuario'];
        $this->id_usuario_cad = Auth::User()->id_usuario;

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}