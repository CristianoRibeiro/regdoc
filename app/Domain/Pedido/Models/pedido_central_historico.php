<?php

namespace App\Domain\Pedido\Models;

use Illuminate\Database\Eloquent\Model;

class pedido_central_historico extends Model
{
    protected $table = 'pedido_central_historico';
    protected $primaryKey = 'id_pedido_historico_central';
    public $timestamps = false;

    // Funções de relacionamento
    public function pedido_central() {
      return $this->belongsTo('App\Domain\Pedido\Models\pedido_central','id_pedido_central');
    }

    public function pedido_central_situacao() {
	    return $this->belongsTo('App\Domain\Pedido\Models\pedido_central_situacao','id_pedido_central_situacao');
	  }

    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
	
}
