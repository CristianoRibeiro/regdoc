<?php
namespace App\Domain\Pedido\Models;

use App\Models\pedido;
use App\Models\tipo_origem;
use Illuminate\Database\Eloquent\Model;

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
}
