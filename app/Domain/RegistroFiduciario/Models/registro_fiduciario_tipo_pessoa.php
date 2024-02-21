<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_tipo_pessoa extends Model
{
    protected $table = 'registro_fiduciario_tipo_pessoa ';
    protected $primaryKey = 'id_registro_fiduciario_tipo_pessoa';
    public $timestamps = false;

    public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\Usuario', 'id_usuario_cad');
    }

    public function registro_fiduciario_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo', 'id_usuario_cad');
    }

}
