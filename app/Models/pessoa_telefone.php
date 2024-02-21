<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pessoa_telefone extends Model
{
	protected $table = 'pessoa_telefone';
	protected $primaryKey = 'id_pessoa_telefone';
    public $timestamps = false;

	// Funções de relacionamento
	public function telefone() {
		return $this->belongsTo(telefone::class,'id_telefone');
	}
}
