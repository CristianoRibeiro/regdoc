<?php
namespace App\Domain\Arisp\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\usuario;

class arisp_boleto extends Model
{
    protected $table = 'arisp_boleto';
    protected $primaryKey = 'id_arisp_boleto';
    public $timestamps = false;
    protected $guarded  = array();

    public function usuario_cad() {
        return $this->belongsTo(usuario::class, 'id_usuario_cad', 'id_usuario');
    }
}
