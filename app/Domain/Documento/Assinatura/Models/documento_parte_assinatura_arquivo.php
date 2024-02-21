<?php
namespace App\Domain\Documento\Assinatura\Models;

use Illuminate\Database\Eloquent\Model;

class documento_parte_assinatura_arquivo extends Model
{
    protected $table = 'documento_parte_assinatura_arquivo';
    protected $primaryKey = 'id_documento_parte_assinatura_arquivo';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento_parte_assinatura()
    {
        return $this->belongsTo('App\Domain\Documento\Assinatura\Models\documento_parte_assinatura', 'id_documento_parte_assinatura');
    }
    public function arquivo_grupo_produto()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }


}
