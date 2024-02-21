<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pagamento_pessoa_lote_arquivo_grupo_produto extends Model
{
    protected $table = 'pagamento_pessoa_lote_arquivo_grupo_produto';
    protected $primaryKey = 'id_pagamento_pessoa_lote_arquivo_grupo_produto';
    public $timestamps = false;

    // Funções especiais
    public function insere($args) {
        $this->id_pagamento_repasse_pessoa_lote = $args['id_pagamento_repasse_pessoa_lote'];
        $this->id_arquivo_grupo_produto = $args['id_arquivo_grupo_produto'];
        $this->dt_cadastro = $args['dt_cadastro'];
        if ($this->save()) {
            return $this;
        } else {
            return;
        }
    }

}
