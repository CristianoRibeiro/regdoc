<?php

namespace App\Domain\Pedido\Models;

use Illuminate\Database\Eloquent\Model;

class pedido_central_situacao extends Model
{
    protected $table = 'pedido_central_situacao';
    protected $primaryKey = 'id_pedido_central_situacao';
    public $timestamps = false;

}
