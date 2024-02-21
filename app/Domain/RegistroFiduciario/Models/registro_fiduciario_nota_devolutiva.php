<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_nota_devolutiva extends Model
{
    protected $table = 'registro_fiduciario_nota_devolutiva';
    protected $primaryKey = 'id_registro_fiduciario_nota_devolutiva';
    public $timestamps = false;

    public function registro_fiduciario_nota_devolutiva_situacao()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_situacao', 'id_registro_fiduciario_nota_devolutiva_situacao');
    }
    public function registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario', 'id_registro_fiduciario');
    }
    public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'registro_fiduciario_nota_devolutiva_arquivo_grupo', 'id_registro_fiduciario_nota_devolutiva', 'id_arquivo_grupo_produto')->wherePivot('in_registro_ativo', 'S');
    }
    public function nota_devolutiva_cumprimento()
    {
        return $this->belongsTo('App\Domain\NotaDevolutiva\Models\nota_devolutiva_cumprimento', 'id_nota_devolutiva_cumprimento');
    }
    public function causas_raiz()
    {
        return $this->belongsToMany('App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_raiz', 'nota_devolutiva_nota_devolutiva_causa_raiz', 'id_registro_fiduciario_nota_devolutiva', 'id_nota_devolutiva_causa_raiz');
    }
    public function nota_devolutiva_nota_devolutiva_causa_raiz()
    {
        return $this->hasMany('App\Domain\NotaDevolutiva\Models\nota_devolutiva_nota_devolutiva_causa_raiz', 'id_registro_fiduciario_nota_devolutiva');
    }
}
