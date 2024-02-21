<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fase_grupo_produto extends Model
{
    protected $table = 'fase_grupo_produto';
    protected $primaryKey = 'id_fase_grupo_produto';
    public $timestamps = false;

    // Funções de relacionamento
    public function fluxo_andamento() {
        return $this->belongsTo(fluxo_andamento::class,'id_fluxo_andamento');
    }
}
