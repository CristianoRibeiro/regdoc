<?php

namespace App\Domain\RegistroFiduciario\Models;

use App\Domain\Usuario\Models\usuario;
use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_comentario extends Model
{
    protected $table = 'registro_fiduciario_comentario';

    protected $primaryKey = 'id_registro_fiduciario_comentario';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registro_fiduciario()
    {
        return $this->belongsTo(registro_fiduciario::class, 'id_registro_fiduciario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(usuario::class, 'id_usuario_cad');
    }

    // Funções de relacionamento
	public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'registro_fiduciario_comentario_arquivo_grupo', 'id_registro_fiduciario_comentario', 'id_arquivo_grupo_produto');
    }
}
