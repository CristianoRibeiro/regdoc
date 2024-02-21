<?php

namespace App\Domain\Usuario\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\Pessoa\Models\pessoa;

class usuario_key extends Model
{
	protected $table = 'usuario_key';
	protected $primaryKey = 'id_usuario_key';
	public $timestamps = false;

	// Funções de relacionamento
	public function usuario()
	{
		return $this->belongsTo(usuario::class,'id_usuario');
	}
	public function pessoa()
	{
        return $this->belongsTo(pessoa::class,'id_pessoa');
    }
}
