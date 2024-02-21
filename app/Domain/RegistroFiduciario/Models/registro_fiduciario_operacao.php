<?php
namespace App\Domain\RegistroFiduciario\Models;

use App\Models\registro_fiduciario_credor;
use App\Models\registro_fiduciario_origem_recursos;
use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_operacao extends Model
{
    protected $table = 'registro_fiduciario_operacao';
    protected $primaryKey = 'id_registro_fiduciario_operacao';

    public $timestamps = false;

    public function registro_fiduciario_credor()
    {
        return $this->belongsTo(registro_fiduciario_credor::class,'id_registro_fiduciario_credor');
    }
    public function registro_fiduciario_origem_recursos()
    {
        return $this->belongsTo(registro_fiduciario_origem_recursos::class,'id_registro_fiduciario_origem_recursos');
    }
}
