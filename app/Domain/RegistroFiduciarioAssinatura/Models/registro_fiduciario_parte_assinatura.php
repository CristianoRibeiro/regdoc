<?php
namespace App\Domain\RegistroFiduciarioAssinatura\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_parte_assinatura extends Model
{
    protected $table = 'registro_fiduciario_parte_assinatura';
    protected $primaryKey = 'id_registro_fiduciario_parte_assinatura';
    public $timestamps = false;

    public function registro_fiduciario_assinatura()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura','id_registro_fiduciario_assinatura');
    }
    public function registro_fiduciario_parte()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte','id_registro_fiduciario_parte');
    }
    public function registro_fiduciario_procurador()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_procurador','id_registro_fiduciario_procurador');
    }
    public function registro_fiduciario_conjuge()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_conjuge','id_registro_fiduciario_conjuge');
    }
    public function registro_fiduciario_parte_assinatura_arquivo()
    {
        return $this->hasMany('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura_arquivo','id_registro_fiduciario_parte_assinatura');
    }
    public function arquivos()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'registro_fiduciario_parte_assinatura_arquivo', 'id_registro_fiduciario_parte_assinatura', 'id_arquivo_grupo_produto');
    }
    public function arquivos_nao_assinados()
    {
        return $this->hasMany('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura_arquivo','id_registro_fiduciario_parte_assinatura')->whereNull('id_arquivo_grupo_produto_assinatura');
    }
    public function arquivos_assinados()
    {
        return $this->hasMany('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura_arquivo','id_registro_fiduciario_parte_assinatura')->whereNotNull('id_arquivo_grupo_produto_assinatura');
    }
}
