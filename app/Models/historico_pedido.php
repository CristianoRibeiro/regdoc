<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class historico_pedido extends Model
{
    protected $table = 'historico_pedido';
    protected $primaryKey = 'id_historico_pedido';
    public $timestamps = false;

    // Funções especiais
    public function insere($args) 
    {
        $this->id_pedido = $args['id_pedido'];
		$this->id_situacao_pedido_grupo_produto = $args['id_situacao_pedido_grupo_produto'];
		$this->id_alcada = $args['id_alcada'];
		$this->de_observacao = $args['de_observacao'];
		$this->id_usuario_cad = Auth::User()->id_usuario;

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}

