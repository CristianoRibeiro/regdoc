<?php

namespace App\Domain\Pedido\Models;

use Illuminate\Database\Eloquent\Model;

class pedido_usuario_senha extends Model
{
    protected $table = 'pedido_usuario_senha';

    protected $primaryKey = 'id_pedido_usuario_senha';

    public $timestamps = false;

    // Funções de relacionamento
    public function pedido_usuario()
    {
        return $this->belongsTo(pedido_usuario::class,'id_pedido_usuario');
    }
}