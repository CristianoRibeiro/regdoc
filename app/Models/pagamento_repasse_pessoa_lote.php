<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pagamento_repasse_pessoa_lote extends Model {

    protected $table = 'pagamento_repasse_pessoa_lote';
    protected $primaryKey = 'id_pagamento_repasse_pessoa_lote';
    public $timestamps = false;
    protected $guarded  = array();

    public function situacao_pagamento_repasse() {
        return $this->belongsTo(situacao_pagamento_repasse::class,'id_situacao_pagamento_repasse');
    }

    public function pessoa() {
        return $this->belongsTo(pessoa::class,'id_pessoa');
    }

    public function pagamento_repasse_parcela()
    {
        return $this->hasMany(pagamento_repasse_parcela::class, 'id_pagamento_repasse_pessoa_lote');
    }

    public function pagamento_repasse_lote(){
        return $this->belongsTo(pagamento_repasse_lote::class,'id_pagamento_repasse_lote');
    }

    public function movimentacao_parcela_repasse() {
        return $this->belongsTo(movimentacao_parcela_repasse::class,'id_movimentacao_parcela_repasse');
    }

    public function comprovante_pagamento() {
        return $this->belongsToMany(arquivo_grupo_produto::class,'pagamento_pessoa_lote_arquivo_grupo_produto','id_pagamento_repasse_pessoa_lote','id_arquivo_grupo_produto');
    }

}
