<?php
namespace App\Domain\Documento\Documento\Models;

use Illuminate\Database\Eloquent\Model;

class documento_comentario extends Model
{
    protected $table = 'documento_comentario';
    protected $primaryKey = 'id_documento_comentario';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento()
    {
        return $this->belongsTo('App\Domain\Documento\Documento\Models\documento', 'id_documento');
    }
    public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'documento_comentario_arquivo_grupo', 'id_documento_comentario', 'id_arquivo_grupo_produto');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
}
