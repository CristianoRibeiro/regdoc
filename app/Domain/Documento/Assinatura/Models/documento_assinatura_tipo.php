<?php
namespace App\Domain\Documento\Assinatura\Models;

use Illuminate\Database\Eloquent\Model;

class documento_assinatura_tipo extends Model
{
    protected $table = 'documento_assinatura_tipo';
    protected $primaryKey = 'id_documento_assinatura_tipo';
    public $timestamps = false;
    protected $guarded  = array();

    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
}
