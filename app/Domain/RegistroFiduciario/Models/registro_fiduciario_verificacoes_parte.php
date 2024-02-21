<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

class registro_fiduciario_verificacoes_parte extends Model
{
    protected $table = 'registro_fiduciario_verificacoes_parte';
    protected $primaryKey = 'id_registro_fiduciario_verificacoes_parte';
    public $timestamps = false;

    public function registro_fiduciario_parte()
    {
        return $this->belongsTo(registro_fiduciario_parte::class, 'id_registro_fiduciario_parte');
    }
}
