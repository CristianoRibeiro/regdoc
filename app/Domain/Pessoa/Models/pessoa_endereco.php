<?php
namespace App\Domain\Pessoa\Models;

use Illuminate\Database\Eloquent\Model;

class pessoa_endereco extends Model
{
	protected $table = 'pessoa_endereco';
	protected $primaryKey = 'id_pessoa_endereco';
    public $timestamps = false;

	// Funções de relacionamento
    public function pessoa() {
        return $this->belongsTo(pessoa::class,'id_pessoa');
    }

	public function endereco() {
		return $this->belongsTo(endereco::class,'id_endereco');
	}
}
