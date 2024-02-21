<?php

namespace App\Domain\VScore\Models;

use Illuminate\Database\Eloquent\Model;

class vscore_transacao extends Model
{
    protected $table = 'vscore_transacao';
    protected $primaryKey = 'id_vscore_transacao';
    public $timestamps = false;

    // Funções de relacionamento
    public function vscore_transacao_situacao()
    {
        return $this->belongsTo('App\Domain\VScore\Models\vscore_transacao_situacao', 'id_vscore_transacao_situacao');
    }
    public function vscore_transacao_lote()
    {
        return $this->belongsTo('App\Domain\VScore\Models\vscore_transacao_lote', 'id_vscore_transacao_lote');
    }
    public function pessoa_origem()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa_origem', 'id_pessoa');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
}
