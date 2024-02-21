<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class situacao_pagamento_repasse extends Model {

    protected $table = 'situacao_pagamento_repasse';
    protected $primaryKey = 'id_situacao_pagamento_repasse';
    public $timestamps = false;
    protected $guarded  = array();

    public function pagamento_repasse_pessoa_lote()
    {
        return $this->hasMany(pagamento_repasse_pessoa_lote::class, 'id_pagamento_repasse_pessoa_lote');
    }
}
