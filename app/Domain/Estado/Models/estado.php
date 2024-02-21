<?php

namespace App\Domain\Estado\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\TabelaEmolumento\Models\estado_tabela_emolumento_tipo;

class estado extends Model
{
    protected $table = 'estado';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;

    protected $guarded  = array();

    public function cidades() {
        return $this->hasMany(cidade::class,'id_estado');
    }
    public function pais() {
        return $this->belongsTo(pais::class,'id_pais');
    }
    public function estado_tabela_emolumento_tipo() {
        return $this->hasMany(estado_tabela_emolumento_tipo::class, 'id_estado')->where('in_registro_ativo', 'S');
    }
}
