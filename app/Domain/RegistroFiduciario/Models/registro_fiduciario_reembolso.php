<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_reembolso extends Model
{
    protected $table = 'registro_fiduciario_reembolso';
    protected $primaryKey = 'id_registro_fiduciario_reembolso';
    public $timestamps = false;

    public function registro_fiduciario() {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario', 'id_registro_fiduciario');
    }

    public function registro_fiduciario_reembolso_situacao() {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_reembolso_situacao', 'id_registro_fiduciario_reembolso_situacao');
    }

    public function usuario_cad() {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }

	public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'registro_fiduciario_reembolso_arquivo_grupo', 'id_registro_fiduciario_reembolso', 'id_arquivo_grupo_produto');
    }
}
