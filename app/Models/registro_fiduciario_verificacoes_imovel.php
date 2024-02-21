<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_verificacoes_imovel extends Model
{
    protected $table = 'registro_fiduciario_verificacoes_imovel';
    protected $primaryKey = 'id_registro_fiduciario_verificacoes_imovel';
    public $timestamps = false;

    public function registro_fiduciario()
    {
        return $this->belongsTo(registro_fiduciario::class, 'id_registro_fiduciario');
    }
}
