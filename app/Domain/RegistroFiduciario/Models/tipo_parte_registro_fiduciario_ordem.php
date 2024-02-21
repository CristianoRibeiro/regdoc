<?php
namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class tipo_parte_registro_fiduciario_ordem extends Model
{
    protected $table = 'tipo_parte_registro_fiduciario_ordem';
    protected $primaryKey = 'id_tipo_parte_registro_fiduciario_ordem';
    public $timestamps = false;

    public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa','id_pessoa');
    }
    public function registro_fiduciario_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo','id_registro_fiduciario_tipo');
    }
    public function tipo_parte_registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\tipo_parte_registro_fiduciario','id_tipo_parte_registro_fiduciario');
    }
}
