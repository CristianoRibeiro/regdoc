<?php

namespace App\Domain\VScore\Models;

use Illuminate\Database\Eloquent\Model;

class vscore_transacao_lote extends Model
{
    protected $table = 'vscore_transacao_lote';
    protected $primaryKey = 'id_vscore_transacao_lote';
    public $timestamps = false;

    // Funções de relacionamento
    public function vscore_transacoes()
    {
        return $this->hasMany('App\Domain\VScore\Models\vscore_transacao', 'id_vscore_transacao_lote');
    }
    public function vscore_transacoes_aguardando()
    {
        return $this->hasMany('App\Domain\VScore\Models\vscore_transacao', 'id_vscore_transacao_lote')->where('id_vscore_transacao_situacao', config('constants.VSCORE.SITUACOES.AGUARDANDO_PROCESSAMENTO'));
    }
    public function vscore_transacoes_erro()
    {
        return $this->hasMany('App\Domain\VScore\Models\vscore_transacao', 'id_vscore_transacao_lote')->where('id_vscore_transacao_situacao', config('constants.VSCORE.SITUACOES.ERRO'));
    }
    public function vscore_transacoes_processadas()
    {
        return $this->hasMany('App\Domain\VScore\Models\vscore_transacao', 'id_vscore_transacao_lote')->where('id_vscore_transacao_situacao', config('constants.VSCORE.SITUACOES.FINALIZADO'));
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
