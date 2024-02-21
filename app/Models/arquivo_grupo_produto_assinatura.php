<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

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

    // Funções especiais
    public function insere($args) {
        $this->id_arquivo_grupo_produto = $args['id_arquivo_grupo_produto'];
        $this->no_arquivo = $args['no_arquivo'];
        $this->no_local_arquivo = $args['no_local_arquivo'];
        $this->no_extensao = $args['no_extensao'];
        $this->in_ass_digital = $args['in_ass_digital'];
		$this->dt_ass_digital = ($args['dt_ass_digital']?$args['dt_ass_digital']:NULL);
        $this->nu_tamanho_kb = $args['nu_tamanho_kb'];
        $this->no_hash = $args['no_hash'];
        $this->no_arquivo_p7s = $args['no_arquivo_p7s'];
        $this->no_hash_p7s = $args['no_hash_p7s'];
		$this->id_usuario_certificado = $args['id_usuario_certificado'];
        $this->no_mime_type = $args['no_mime_type'];
		$this->id_usuario_cad = Auth::User()->id_usuario;

        if ($this->save()) {
            return $this;
        } else {
            return;
        }
    }
}
