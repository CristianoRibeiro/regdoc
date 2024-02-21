<?php
namespace App\Domain\Documento\Documento\Models;

use Illuminate\Database\Eloquent\Model;

class documento_observador extends Model
{
    protected $table = 'documento_observador';
    protected $primaryKey = 'id_documento_observador';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento()
    {
        return $this->belongsTo('App\Domain\Documento\Documento\Models\documento', 'id_documento');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
}
