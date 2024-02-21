<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_custodiante extends Model
{
    protected $table = 'registro_fiduciario_custodiante';
    protected $primaryKey = 'id_registro_fiduciario_custodiante';
    public $timestamps = false;

    public function cidade()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade');
    }
}
