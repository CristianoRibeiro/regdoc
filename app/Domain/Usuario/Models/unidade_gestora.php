<?php

namespace App\Domain\Usuario\Models;

use App\Domain\Estado\Models\cidade;
use Illuminate\Database\Eloquent\Model;

class unidade_gestora extends Model
{
    protected $table = 'unidade_gestora';

    protected $primaryKey = 'id_unidade_gestora';

    public $timestamps = false;

    // Funções de relacionamento
    public function cidade()
    {
        return $this->belongsTo(cidade::class,'id_cidade');
    }
}