<?php

namespace App\Domain\RegistroFiduciario\Models;

use App\Domain\CanaisPdv\Models\canal_pdv_parceiro;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_canal_pdv_parceiro extends Model
{
    protected $table = 'registro_fiduciario_canal_pdv_parceiro';

    protected $primaryKey = 'id_registro_fiduciario_canal_pdv_parceiro';

    public $timestamps = false;

    public function registro_fiduciario()
    {
        return $this->belongsTo(registro_fiduciario::class, 'id_registro_fiduciario');
    }

    public function canal_pdv_parceiro()
    {
        return $this->belongsTo(canal_pdv_parceiro::class, 'id_canal_pdv_parceiro');
    }
}