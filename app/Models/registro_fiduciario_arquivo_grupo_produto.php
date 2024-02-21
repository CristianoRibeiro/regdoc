<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_arquivo_grupo_produto extends Model
{
	protected $table = 'registro_fiduciario_arquivo_grupo_produto';
	protected $primaryKey = 'id_registro_fiduciario_arquivo_grupo_produto';
    public $timestamps = false;

	// Funções de relacionamento
	public function arquivo_grupo_produto() {
		return $this->belongsTo(arquivo_grupo_produto::class,'id_arquivo_grupo_produto');
	}
}
