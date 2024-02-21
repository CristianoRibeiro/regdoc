<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pagamento_repasse_parcela extends Model {

    protected $table = 'pagamento_repasse_parcela';
    protected $primaryKey = 'id_pagamento_repasse_parcela';
    public $timestamps = false;
    protected $guarded  = array();

    public function pagamento_repasse_pessoa_lote() {
        return $this->belongsTo(pagamento_repasse_pessoa_lote::class,'id_pagamento_repasse_pessoa_lote');
    }

    public function movimentacao_parcela_repasse() {
        return $this->belongsTo(movimentacao_parcela_repasse::class,'id_movimentacao_parcela_repasse');
    }

}

