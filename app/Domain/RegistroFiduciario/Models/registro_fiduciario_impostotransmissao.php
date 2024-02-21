<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class registro_fiduciario_impostotransmissao extends Model
{
    protected $table = 'registro_fiduciario_impostotransmissao';
    protected $primaryKey = 'id_registro_fiduciario_impostotransmissao';
    public $timestamps = false;

    public function registro_fiduciario()
    {
        return $this->hasOne(registro_fiduciario::class, 'id_registro_fiduciario');
    }
}
