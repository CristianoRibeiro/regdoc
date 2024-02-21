<?php

namespace App\Domain\Configuracao\Models;

use Illuminate\Database\Eloquent\Model;

class configuracao_tipo_pessoa extends Model
{
    protected $table = 'configuracao_tipo_pessoa';
    protected $primaryKey = 'id_configuracao_tipo_pessoa';
    public $timestamps = false;

    public function configuracao()
    {
        return $this->belongsTo('App\Domain\Configuracao\Models\configuracao', 'id_configuracao');
    }
    public function tipo_pessoa()
    {
        return $this->belongsTo('App\Models\tipo_pessoa', 'id_tipo_pessoa');
    }
}
