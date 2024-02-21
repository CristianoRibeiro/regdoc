<?php

namespace App\Domain\RegistroFiduciario\Models;

use App\Models\pedido_usuario;
use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_conjuge extends Model
{
    protected $table = 'registro_fiduciario_conjuge';

    protected $primaryKey = 'id_registro_fiduciario_conjuge';

    public $timestamps = false;

    public function pedido_usuario() {
        return $this->belongsTo(pedido_usuario::class,'id_pedido_usuario');
    }
}
