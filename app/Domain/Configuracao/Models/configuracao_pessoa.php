<?php

namespace App\Domain\Configuracao\Models;

use Illuminate\Database\Eloquent\Model;

class configuracao_pessoa extends Model
{
    protected $table = 'configuracao_pessoa';
    protected $primaryKey = 'id_configuracao_pessoa';
    public $timestamps = false;

    public function configuracao()
    {
        return $this->belongsTo('App\Domain\Configuracao\Models\configuracao', 'id_configuracao');
    }
    public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
}
