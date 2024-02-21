<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_credor_responsavel extends Model
{
    protected $table = 'registro_fiduciario_credor_responsavel';
    protected $primaryKey = 'id_registro_fiduciario_credor_responsavel';
    public $timestamps = false;

    public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
    public function procuracao()
    {
        return $this->belongsTo('App\Domain\Procuracao\Models\procuracao', 'id_procuracao');
    }
}
