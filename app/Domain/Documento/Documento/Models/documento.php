<?php
namespace App\Domain\Documento\Documento\Models;

use Illuminate\Database\Eloquent\Model;

class documento extends Model
{
    protected $table = 'documento';
    protected $primaryKey = 'id_documento';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento_tipo()
    {
        return $this->belongsTo('App\Domain\Documento\Documento\Models\documento_tipo', 'id_documento_tipo');
    }
    public function pedido()
    {
        return $this->belongsTo('App\Domain\Pedido\Models\pedido','id_pedido');
    }
    public function documento_parte()
    {
        return $this->hasMany('App\Domain\Documento\Parte\Models\documento_parte', 'id_documento');
    }
    public function documento_assinatura()
    {
        return $this->hasMany('App\Domain\Documento\Assinatura\Models\documento_assinatura', 'id_documento')->orderBy('id_documento_assinatura_tipo', 'ASC')->orderBy('dt_cadastro', 'DESC');;
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
    public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'documento_arquivo', 'id_documento', 'id_arquivo_grupo_produto');
    }
    public function documento_comentario()
    {
        return $this->hasMany('App\Domain\Documento\Documento\Models\documento_comentario', 'id_documento')->orderBy('dt_cadastro','DESC');
    }
    public function documento_observador()
    {
        return $this->hasMany('App\Domain\Documento\Documento\Models\documento_observador', 'id_documento')->orderBy('dt_cadastro','DESC');
    }
    public function documento_arquivo()
    {
        return $this->hasMany('App\Domain\Documento\Documento\Models\documento_arquivo', 'id_documento');
    }
    public function cidade_foro()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade_foro', 'id_cidade');
    }
    public function documento_parte_cessionaria()
    {
        return $this->hasOne('App\Domain\Documento\Parte\Models\documento_parte', 'id_documento')->where('id_documento_parte_tipo',config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'));
    }
    public function documento_parte_cedente()
    {
        return $this->hasOne('App\Domain\Documento\Parte\Models\documento_parte', 'id_documento')->where('id_documento_parte_tipo',config('constants.DOCUMENTO.PARTES.ID_CEDENTE'));
    }
    public function documento_administradora_cedente()
    {
        return $this->hasOne('App\Domain\Documento\Parte\Models\documento_parte', 'id_documento')->where('id_documento_parte_tipo',config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE'));
    }
    public function documento_escritorio_cobranca()
    {
        return $this->hasOne('App\Domain\Documento\Parte\Models\documento_parte', 'id_documento')->where('id_documento_parte_tipo',config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'));
    }
    public function documento_escritorio_advocacia()
    {
        return $this->hasOne('App\Domain\Documento\Parte\Models\documento_parte', 'id_documento')->where('id_documento_parte_tipo',config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'));
    }
}
