<?php
namespace App\Domain\Pedido\Models;

use Illuminate\Database\Eloquent\Model;

class historico_pedido extends Model
{
    protected $table = 'historico_pedido';
    protected $primaryKey = 'id_historico_pedido';
    public $timestamps = false;

    public function situacao_pedido_grupo_produto()
    {
        return $this->belongsTo('App\Models\situacao_pedido_grupo_produto', 'id_situacao_pedido_grupo_produto');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
}
