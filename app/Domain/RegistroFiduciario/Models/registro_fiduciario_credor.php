<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_credor extends Model
{
    protected $table = 'registro_fiduciario_credor';
    protected $primaryKey = 'id_registro_fiduciario_credor';
    public $timestamps = false;

    public function agencia()
    {
        return $this->belongsTo('App\Models\agencia', 'id_agencia');
    }
    public function cidade()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade');
    }
    public function registro_fiduciario_credor_responsavel()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_credor_responsavel', 'id_registro_fiduciario_credor');
    }
}
