<?php

namespace App\Domain\Procuracao\Models;

use Illuminate\Database\Eloquent\Model;

class procuracao extends Model
{
    protected $table = 'procuracao';
    protected $primaryKey = 'id_procuracao';
    public $timestamps = false;

    public function tipo_instrumento()
    {
        return $this->belongsTo('App\Domain\Procuracao\Models\procuracao_tipo_instrumento', 'id_procuracao_tipo_instrumento');
    }
    public function procuracao_arquivo_grupo()
    {
        return $this->hasMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_procuracao');
    }
    public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'procuracao_arquivo_grupo', 'id_procuracao', 'id_arquivo_grupo_produto');
    }
}
