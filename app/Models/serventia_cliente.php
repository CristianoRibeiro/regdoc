<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class serventia_cliente extends Model 
{
	protected $table = 'serventia_cliente';
	protected $primaryKey = 'id_serventia_cliente';
    public $timestamps = false;

	// Funções de relacionamento
	public function serventia() {
		return $this->belongsTo(serventia::class,'id_serventia');
	}
}
