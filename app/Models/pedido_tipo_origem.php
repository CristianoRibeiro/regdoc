<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class pedido_tipo_origem extends Model 
{
	protected $table = 'pedido_tipo_origem';
	protected $primaryKey = 'id_pedido_tipo_origem';
    public $timestamps = false;
	
	// Funções de relacionamento
	public function pedido() {
		return $this->belongsTo(pedido::class,'id_pedido');
	}
	public function tipo_origem() {
		return $this->belongsTo(tipo_origem::class,'id_tipo_origem');
	}

    // Funções especiais
    public function insere($args) 
    {
        $this->id_tipo_origem = $args['id_tipo_origem'];
		$this->id_pedido = $args['id_pedido'];
        $this->ip_origem = (isset($args['ip_origem'])?$args['ip_origem']:NULL);
        $this->id_usuario_cad = Auth::User()->id_usuario;

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}
