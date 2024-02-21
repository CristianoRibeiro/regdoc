<?php
namespace App\Domain\Procuracao\Models;

use Illuminate\Database\Eloquent\Model;

class procuracao_arquivo_grupo extends Model
{
	protected $table = 'procuracao_arquivo_grupo';
	protected $primaryKey = 'id_procuracao_arquivo_grupo';
    public $timestamps = false;

    // Funções de relacionamento
    public function procuracao()
    {
    	return $this->belongsTo('App\Domain\Procuracao\Models\usuario','id_procuracao');
    }
	public function arquivo_grupo_produto()
    {
    	return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto','id_arquivo_grupo_produto');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario','id_usuario_cad','id_usuario');
    }
}
