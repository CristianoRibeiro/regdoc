<?php

namespace App\Domain\RegistroFiduciario\Models;

use App\Domain\Usuario\Models\usuario;
use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_operador extends Model
{
    protected $table = 'registro_fiduciario_operador';
    protected $primaryKey = 'id_registro_fiduciario_operador';
    public $timestamps = false;

    // Funções de relacionamento
    public function registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario', 'id_registro_fiduciario');
    }
    public function usuario() 
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario');
    }
    public function usuario_cad() 
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
    public function usuario_alt() 
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_alt', 'id_usuario');
    }
    public function usuario_del() 
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_del', 'id_usuario');
    }
}
