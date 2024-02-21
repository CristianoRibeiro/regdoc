<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_endereco extends Model
{
    protected $table = 'registro_fiduciario_endereco';
    protected $primaryKey = 'id_registro_fiduciario_endereco';
    public $timestamps = false;

    // Relações
    public function cidade()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade');
    }
    public function registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario', 'id_registro_fiduciario');
    }
}
