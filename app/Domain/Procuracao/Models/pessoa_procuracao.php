<?php

namespace App\Domain\Procuracao\Models;

use Illuminate\Database\Eloquent\Model;

class pessoa_procuracao extends Model
{
    protected $table = 'pessoa_procuracao';
    protected $primaryKey = 'id_pessoa_procuracao';
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
