<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pagamento_repasse_lote extends Model {

    protected $table = 'pagamento_repasse_lote';
    protected $primaryKey = 'id_pagamento_repasse_lote';
    public $timestamps = false;
    protected $guarded  = array();

    public function pessoa() {
        return $this->belongsTo(pessoa::class,'id_pessoa');
    }

    public function situacao_pagamento_repasse() {
        return $this->belongsTo(situacao_pagamento_repasse::class,'id_situacao_pagamento_repasse');
    }

    public function pagamento_repasse_pessoa_lote() {
        return $this->hasMany(pagamento_repasse_pessoa_lote::class, 'id_pagamento_repasse_lote');
    }
}
