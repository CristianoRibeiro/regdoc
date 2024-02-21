<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_parte_arquivo_grupo extends Model
{
    protected $table = 'registro_fiduciario_parte_arquivo_grupo';
    protected $primaryKey = 'id_registro_fiduciario_parte_arquivo_grupo';
    public $timestamps = false;
    protected $guarded  = array();

	// Funções de relacionamento
    public function arquivo_grupo_produto()
    {
        return $this->belongsTo(arquivo_grupo_produto::class,'id_arquivo_grupo_produto');
    }
}
