<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class movimentacao_parcela_repasse extends Model {

    protected $table = 'movimentacao_parcela_repasse';
    protected $primaryKey = 'id_movimentacao_parcela_repasse';
    public $timestamps = false;
    protected $guarded  = array();

    public function movimentacao_parcela() {
        return $this->belongsTo(movimentacao_parcela::class,'id_movimentacao_parcela');
    }

    public function pedido() {
        return $this->belongsTo(pedido::class,'id_pedido');
    }

    public function serventia() {
        return $this->belongsTo(serventia::class, 'id_serventia');
    }
}
