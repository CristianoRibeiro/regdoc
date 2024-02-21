<?php
namespace App\Domain\Documento\Documento\Models;

use Illuminate\Database\Eloquent\Model;

class documento_arquivo extends Model
{
    protected $table = 'documento_arquivo';
    protected $primaryKey = 'id_documento_arquivo';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento()
    {
        return $this->belongsTo('App\Domain\Documento\Documento\Models\documento', 'id_documento');
    }
    public function arquivo_grupo_produto()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto');
    }
}
