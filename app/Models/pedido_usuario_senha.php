<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Hash;
use Crypt;

class pedido_usuario_senha extends Model 
{
    protected $table = 'pedido_usuario_senha';
    protected $primaryKey = 'id_pedido_usuario_senha';
    public $timestamps = false;
    
    // Funções de relacionamento
    public function pedido_usuario()
    {
        return $this->belongsTo(pedido_usuario::class,'id_pedido_usuario');
    }

    // Funções especiais
    public function insere($args) 
    {
        $this->id_pedido_usuario = $args['id_pedido_usuario'];
		$this->senha = Hash::make($args['senha']);
        $this->senha_crypt = Crypt::encryptString($args['senha']);
		$this->in_registro_ativo = (isset($args['in_registro_ativo'])?$args['in_registro_ativo']:'S');

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}
