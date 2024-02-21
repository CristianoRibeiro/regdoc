<?php
namespace App\Domain\Arquivo\Models;

use Illuminate\Database\Eloquent\Model;

class arquivo_grupo_produto_assinatura extends Model
{
	protected $table = 'arquivo_grupo_produto_assinatura';
	protected $primaryKey = 'id_arquivo_grupo_produto_assinatura';
    public $timestamps = false;

    // Funções de relacionamento
    public function arquivo_grupo_produto()
    {
    	return $this->belongsTo(arquivo_grupo_produto::class,'id_arquivo_grupo_produto');
    }
    public function tipo_arquivo_grupo_produto()
    {
    	return $this->belongsTo(tipo_arquivo_grupo_produto::class,'id_tipo_arquivo_grupo_produto');
    }
    public function usuario_certificado()
    {
        return $this->belongsTo(usuario_certificado::class,'id_usuario_certificado');
    }
    public function usuario_cad()
    {
        return $this->belongsTo(usuario::class,'id_usuario_cad','id_usuario');
    }
}
