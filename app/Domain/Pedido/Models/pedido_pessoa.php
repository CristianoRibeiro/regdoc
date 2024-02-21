<?php
namespace App\Domain\Pedido\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\Pedido\Models\pedido;
use App\Domain\Pessoa\Models\pessoa;

class pedido_pessoa extends Model 
{
	protected $table = 'pedido_pessoa';
	protected $primaryKey = 'id_pedido_pessoa';
    public $timestamps = false;
	
	// Funções de relacionamento
	public function pedido() {
		return $this->belongsTo(pedido::class,'id_pedido');
	}
	public function pessoa() {
		return $this->belongsTo(pessoa::class,'id_pessoa');
	}
}
