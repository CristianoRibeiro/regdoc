<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fluxo_andamento_situacao extends Model
{
    protected $table = 'fluxo_andamento_situacao';
    protected $primaryKey = 'id_fluxo_andamento_situacao';
    public $timestamps = false;

    // Funções de relacionamento
    public function fluxo_andamento() {
        return $this->belongsTo(fluxo_andamento::class,'id_fluxo_andamento');
    }
    public function tipo_pessoa() {
        return $this->belongsTo(tipo_pessoa::class,'id_tipo_pessoa');
    }
    public function situacao_pedido_grupo_produto() {
        return $this->belongsTo(situacao_pedido_grupo_produto::class,'id_situacao_pedido_grupo_produto');
    }
}
