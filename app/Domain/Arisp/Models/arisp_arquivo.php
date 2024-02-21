<?php

namespace App\Domain\Arisp\Models;

use Illuminate\Database\Eloquent\Model;

class arisp_arquivo extends Model
{
    protected $table = 'arisp_arquivo';
    protected $primaryKey = 'id_arisp_arquivo';
    public $timestamps = false;

    public function arquivo_grupo_produto() {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto');
    }
}
