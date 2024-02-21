<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class endereco extends Model 
{
	protected $table = 'endereco';
	protected $primaryKey = 'id_endereco';
    public $timestamps = false;
	
	// Funções de relacionamento
	public function cidade() {
		return $this->belongsTo(cidade::class,'id_cidade');
	}
}
