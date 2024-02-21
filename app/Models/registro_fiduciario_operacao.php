<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_operacao extends Model
{
    protected $table = 'registro_fiduciario_operacao';
    protected $primaryKey = 'id_registro_fiduciario_operacao';
    public $timestamps = false;

    public function registro_fiduciario_credor()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_credor', 'id_registro_fiduciario_credor');
    }
    public function registro_fiduciario_origem_recursos()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_origem_recursos', 'id_registro_fiduciario_origem_recursos');
    }
}
