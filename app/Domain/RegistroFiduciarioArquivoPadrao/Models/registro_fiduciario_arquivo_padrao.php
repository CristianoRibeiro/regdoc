<?php

namespace App\Domain\RegistroFiduciarioArquivoPadrao\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_arquivo_padrao extends Model
{
    protected $table = 'registro_fiduciario_arquivo_padrao';
    protected $primaryKey = 'id_registro_fiduciario_arquivo_padrao';
    public $timestamps = false;

    public function arquivo_grupo_produto()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto');
    }
    public function registro_fiduciario_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo', 'id_registro_fiduciario_tipo');
    }
    public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
    public function tipo_arquivo_grupo_produto()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\tipo_arquivo_grupo_produto', 'id_tipo_arquivo_grupo_produto');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad');
    }
}
