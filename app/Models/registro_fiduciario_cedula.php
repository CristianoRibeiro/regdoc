<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_cedula extends Model
{
    protected $table = 'registro_fiduciario_cedula';
    protected $primaryKey = 'id_registro_fiduciario_cedula';
    public $timestamps = false;

    public function registro_fiduciario_cedula_tipo()
    {
        return $this->belongsTo(registro_fiduciario_cedula_tipo::class, 'id_registro_fiduciario_cedula_tipo');
    }
    public function registro_fiduciario_cedula_fracao()
    {
        return $this->belongsTo(registro_fiduciario_cedula_fracao::class, 'id_registro_fiduciario_cedula_fracao');
    }
    public function registro_fiduciario_cedula_especie()
    {
        return $this->belongsTo(registro_fiduciario_cedula_especie::class, 'id_registro_fiduciario_cedula_especie');
    }
}
