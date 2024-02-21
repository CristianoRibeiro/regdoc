<?php

namespace App\Domain\Registro\Models;

use Illuminate\Database\Eloquent\Model;

class registro_tipo_parte_tipo_pessoa extends Model
{
    protected $table = 'registro_tipo_parte_tipo_pessoa';
    protected $primaryKey = 'id_registro_tipo_parte_tipo_pessoa';
    public $timestamps = false;

    public function registro_fiduciario_tipo() {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo', 'id_registro_fiduciario_tipo');
    }
    public function tipo_parte_registro_fiduciario() {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\tipo_parte_registro_fiduciario', 'id_tipo_parte_registro_fiduciario');
    }
    public function pessoa() {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
    public function usuario() {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }

}