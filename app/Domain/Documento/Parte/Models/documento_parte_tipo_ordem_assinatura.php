<?php
namespace App\Domain\Documento\Parte\Models;

use Illuminate\Database\Eloquent\Model;

class documento_parte_tipo_ordem_assinatura extends Model
{
    protected $table = 'documento_parte_tipo_ordem_assinatura';
    protected $primaryKey = 'id_documento_parte_tipo_ordem_assinatura';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento_parte_tipo()
    {
        return $this->belongsTo('App\Domain\Documento\Parte\Models\documento_parte_tipo', 'id_documento_parte_tipo');
    }
    public function documento_tipo()
    {
        return $this->belongsTo('App\Domain\Documento\Documento\Models\documento_tipo', 'id_documento_tipo');
    }
    public function documento_assinatura_tipo()
    {
        return $this->belongsTo('App\Domain\Documento\Assinatura\Models\documento_assinatura_tipo', 'id_documento_assinatura_tipo');
    }
    public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
}
