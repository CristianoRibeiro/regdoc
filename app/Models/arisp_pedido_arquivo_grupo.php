<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class arisp_pedido_arquivo_grupo extends Model
{
    protected $table = 'arisp_pedido_arquivo_grupo';
    protected $primaryKey = 'id_arisp_pedido_arquivo_grupo';
    public $timestamps = false;

	// Funções de relacionamento
    public function arisp_pedido()
    {
        return $this->belongsTo(arisp_pedido::class, 'id_arisp_pedido');
    }
    public function arquivo_grupo_produto()
    {
    	return $this->belongsTo(arquivo_grupo_produto::class,'id_arquivo_grupo_produto');
    }

}
