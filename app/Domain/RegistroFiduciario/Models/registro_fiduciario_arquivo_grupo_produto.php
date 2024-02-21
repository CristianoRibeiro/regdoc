<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_arquivo_grupo_produto extends Model
{
	protected $table = 'registro_fiduciario_arquivo_grupo_produto';
	protected $primaryKey = 'id_registro_fiduciario_arquivo_grupo_produto';
    public $timestamps = false;

	// Funções de relacionamento
	public function arquivo_grupo_produto()
	{
		return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto');
	}
}
