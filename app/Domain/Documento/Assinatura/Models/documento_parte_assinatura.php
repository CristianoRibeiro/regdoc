<?php
namespace App\Domain\Documento\Assinatura\Models;

use Illuminate\Database\Eloquent\Model;

class documento_parte_assinatura extends Model
{
    protected $table = 'documento_parte_assinatura';
    protected $primaryKey = 'id_documento_parte_assinatura';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento_parte()
    {
        return $this->belongsTo('App\Domain\Documento\Parte\Models\documento_parte', 'id_documento_parte');
    }
    public function documento_assinatura()
    {
        return $this->belongsTo('App\Domain\Documento\Assinatura\Models\documento_assinatura', 'id_documento_assinatura');
    }
    public function documento_procurador()
    {
        return $this->belongsTo('App\Domain\Documento\Parte\Models\documento_procurador', 'id_documento_procurador');
    }
    public function documento_parte_assinatura_arquivo()
    {
        return $this->hasMany('App\Domain\Documento\Assinatura\Models\documento_parte_assinatura_arquivo', 'id_documento_parte_assinatura');
    }
    public function arquivos_nao_assinados()
    {
        return $this->hasMany('App\Domain\Documento\Assinatura\Models\documento_parte_assinatura_arquivo', 'id_documento_parte_assinatura')->whereNull('id_arquivo_grupo_produto_assinatura');
    }
    public function arquivos_assinados()
    {
        return $this->hasMany('App\Domain\Documento\Assinatura\Models\documento_parte_assinatura_arquivo', 'id_documento_parte_assinatura')->whereNotNull('id_arquivo_grupo_produto_assinatura');
    }
}
