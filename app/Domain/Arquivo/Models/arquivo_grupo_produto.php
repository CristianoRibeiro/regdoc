<?php
namespace App\Domain\Arquivo\Models;

use Illuminate\Database\Eloquent\Model;

class arquivo_grupo_produto extends Model
{
	protected $table = 'arquivo_grupo_produto';
	protected $primaryKey = 'id_arquivo_grupo_produto';
    public $timestamps = false;

    // Funções de relacionamento
    public function grupo_produto()
    {
    	return $this->belongsTo('App\Models\grupo_produto','id_grupo_produto');
    }
    public function tipo_arquivo_grupo_produto()
    {
    	return $this->belongsTo('App\Domain\Arquivo\Models\tipo_arquivo_grupo_produto','id_tipo_arquivo_grupo_produto');
    }
    public function usuario_certificado()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario_certificado','id_usuario_certificado');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario','id_usuario_cad','id_usuario');
    }
    public function arquivo_grupo_produto_composicao()
    {
        return $this->hasMany('App\Domain\Arquivo\Models\arquivo_grupo_produto_composicao','id_arquivo_grupo_produto');
    }
	public function arquivo_grupo_produto_assinatura()
	{
		return $this->hasMany('App\Domain\Arquivo\Models\arquivo_grupo_produto_assinatura','id_arquivo_grupo_produto');
	}
	public function arisp_arquivo()
    {
    	return $this->hasOne('App\Models\arisp_arquivo','id_arquivo_grupo_produto');
    }
}
