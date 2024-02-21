<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class arisp_pedido_historico extends Model
{
    protected $table = 'arisp_pedido_historico';
    protected $primaryKey = 'id_arisp_pedido_historico';
    public $timestamps = false;
    protected $guarded  = array();

	// Funções de relacionamento
    public function arisp_pedido_status()
    {
        return $this->belongsTo(arisp_pedido_status::class, 'id_arisp_pedido_status');
    }

    public function arisp_pedido()
    {
        return $this->belongsTo(arisp_pedido::class, 'id_arisp_pedido');
    }

    public function usuario_cad()
    {
        return $this->belongsTo(usuario::class, 'id_usuario_cad');
    }
}
