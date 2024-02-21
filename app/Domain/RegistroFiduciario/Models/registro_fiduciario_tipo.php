<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\produto;

class registro_fiduciario_tipo extends Model
{
    protected $table = 'registro_fiduciario_tipo';
    protected $primaryKey = 'id_registro_fiduciario_tipo';
    public $timestamps = false;

    public function produto()
    {
        return $this->belongsTo(produto::class, 'id_produto');
    }
}
