<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class situacao_pedido_grupo_produto extends Model
{
    protected $table = 'situacao_pedido_grupo_produto';
    protected $primaryKey = 'id_situacao_pedido_grupo_produto';
    public $timestamps = false;
}
