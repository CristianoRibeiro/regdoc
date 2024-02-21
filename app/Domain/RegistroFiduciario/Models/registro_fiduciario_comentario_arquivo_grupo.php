<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_comentario_arquivo_grupo extends Model
{
	protected $table = 'registro_fiduciario_comentario_arquivo_grupo';
	protected $primaryKey = 'id_registro_fiduciario_comentario_arquivo_grupo';
    public $timestamps = false;

	// Funções de relacionamento
	public function registro_fiduciario_comentario()
	{
		return $this->belongsTo(registro_fiduciario_comentario::class, 'id_registro_fiduciario_comentario');
	}

}
