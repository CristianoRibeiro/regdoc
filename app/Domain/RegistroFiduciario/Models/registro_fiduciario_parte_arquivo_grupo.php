<?php
namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_parte_arquivo_grupo extends Model
{
    protected $table = 'registro_fiduciario_parte_arquivo_grupo';
    protected $primaryKey = 'id_registro_fiduciario_parte_arquivo_grupo';
    public $timestamps = false;

	// Funções de relacionamento
    public function arquivo_grupo_produto()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto');
    }
}
