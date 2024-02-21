<?php
namespace App\Domain\Documento\Assinatura\Models;

use Illuminate\Database\Eloquent\Model;

class documento_assinatura extends Model
{
    protected $table = 'documento_assinatura';
    protected $primaryKey = 'id_documento_assinatura';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento_assinatura_tipo()
    {
        return $this->belongsTo('App\Domain\Documento\Assinatura\Models\documento_assinatura_tipo', 'id_documento_assinatura_tipo');
    }
    public function documento()
    {
        return $this->belongsTo('App\Domain\Documento\Documento\Models\documento', 'id_documento');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
    public function documento_parte_assinatura()
    {
        return $this->hasMany('App\Domain\Documento\Assinatura\Models\documento_parte_assinatura', 'id_documento_assinatura')->orderBy('nu_ordem_assinatura', 'ASC');
    }

}
