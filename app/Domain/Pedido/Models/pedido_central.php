<?php

namespace App\Domain\Pedido\Models;

use Illuminate\Database\Eloquent\Model;

class pedido_central extends Model
{
    protected $table = 'pedido_central';
    protected $primaryKey = 'id_pedido_central';
    public $timestamps = false;

    // Funções de relacionamento
	public function pedido() {
		return $this->belongsTo('App\Domain\Pedido\Models\pedido','id_pedido');
	}

    public function pedido_central_situacao() {
		return $this->belongsTo('App\Domain\Pedido\Models\pedido_central_situacao','id_pedido_central_situacao');
	}

    public function pedido_central_historico()
    {
        return $this->hasMany('App\Domain\Pedido\Models\pedido_central_historico','id_pedido_central')->orderBy('dt_historico','desc');
    }

}
