<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_reembolso_arquivo_grupo extends Model
{
    protected $table = 'registro_fiduciario_reembolso_arquivo_grupo';
    protected $primaryKey = 'id_registro_fiduciario_reembolso_arquivo_grupo';
    public $timestamps = false;

    public function registro_fiduciario_reembolso() {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_reembolso', 'id_registro_fiduciario_reembolso');
    }

    public function arquivo_grupo_produto() {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto');
    }

    public function usuario_cad() {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }

}
