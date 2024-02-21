<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pedido_usuario extends Model
{
	protected $table = 'pedido_usuario';
	protected $primaryKey = 'id_pedido_usuario';
    public $timestamps = false;

	// Funções de relacionamento
	public function pedido() {
		return $this->belongsTo(pedido::class,'id_pedido');
	}
	public function usuario() {
		return $this->belongsTo(usuario::class,'id_usuario');
	}
    public function pedido_usuario_senha()
    {
        return $this->hasOne(pedido_usuario_senha::class,'id_pedido_usuario')->where('in_registro_ativo','S');
    }

    // Funções especiais
    public function insere($args)
    {
        $this->id_pedido = $args['id_pedido'];
		$this->id_usuario = $args['id_usuario'];
		$this->token = $args['token'];

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}
