<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Ramsey\Uuid\Uuid;

class arquivo_grupo_produto extends Model
{
	protected $table = 'arquivo_grupo_produto';
	protected $primaryKey = 'id_arquivo_grupo_produto';
    public $timestamps = false;

    // Funções de relacionamento
    public function grupo_produto()
    {
    	return $this->belongsTo(grupo_produto::class,'id_grupo_produto');
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
    public function arquivo_grupo_produto_composicao()
    {
        return $this->hasMany(arquivo_grupo_produto_composicao::class,'id_arquivo_grupo_produto');
    }
	public function arquivo_grupo_produto_assinatura()
	{
		return $this->hasMany(arquivo_grupo_produto_assinatura::class,'id_arquivo_grupo_produto');
	}
	public function arisp_arquivo()
    {
    	return $this->hasOne(arisp_arquivo::class,'id_arquivo_grupo_produto');
    }

    // Funções especiais
    public function insere($args) {
		$this->uuid = Uuid::uuid4();
        $this->id_grupo_produto = $args['id_grupo_produto'];
        $this->id_tipo_arquivo_grupo_produto = $args['id_tipo_arquivo_grupo_produto'];
        $this->no_arquivo = $args['no_arquivo'];
        $this->no_descricao_arquivo = ($args['no_descricao_arquivo']?$args['no_descricao_arquivo']:NULL);
        $this->no_local_arquivo = $args['no_local_arquivo'];
        $this->id_usuario_cad = Auth::User()->id_usuario;
        $this->no_extensao = $args['no_extensao'];
        $this->in_ass_digital = $args['in_ass_digital'] ?? 'N';
        $this->nu_tamanho_kb = $args['nu_tamanho_kb'];
        $this->no_hash = $args['no_hash'];
        $this->dt_ass_digital = $args['dt_ass_digital'] ?? NULL;
        $this->id_usuario_certificado = $args['id_usuario_certificado'] ?? NULL;
        $this->no_arquivo_p7s = $args['no_arquivo_p7s'] ?? NULL;
        $this->no_hash_p7s = $args['no_hash_p7s'] ?? NULL;
        $this->no_mime_type = $args['no_mime_type'];
        $this->no_url_origem = $args['no_url_origem'] ?? NULL;

        if ($this->save()) {
            return $this;
        } else {
            return;
        }
    }

    public function atualiza($args)
    {
        $arquivo_grupo_produto = arquivo_grupo_produto::find($args['id_arquivo_grupo_produto']);

		$arquivo_grupo_produto->id_grupo_produto = $args['id_grupo_produto'];
		$arquivo_grupo_produto->id_tipo_arquivo_grupo_produto = $args['id_tipo_arquivo_grupo_produto'];
		$arquivo_grupo_produto->no_arquivo = $args['no_arquivo'];
        $arquivo_grupo_produto->no_descricao_arquivo = (isset($args['no_descricao_arquivo'])?$args['no_descricao_arquivo']:NULL);
        $arquivo_grupo_produto->no_local_arquivo = $args['no_local_arquivo'];
		$arquivo_grupo_produto->no_extensao = $args['no_extensao'];
		$arquivo_grupo_produto->in_ass_digital = $args['in_ass_digital'];
		$arquivo_grupo_produto->nu_tamanho_kb = $args['nu_tamanho_kb'];
		$arquivo_grupo_produto->no_hash = $args['no_hash'];
		$arquivo_grupo_produto->dt_ass_digital = ($args['dt_ass_digital']?\Carbon\Carbon::parse($args['dt_ass_digital'])->format('Y-m-d h:i:s'):NULL);
		$arquivo_grupo_produto->id_usuario_certificado = $args['id_usuario_certificado'];
		$arquivo_grupo_produto->no_arquivo_p7s = $args['no_arquivo_p7s'];
		$arquivo_grupo_produto->no_hash_p7s = $args['no_hash_p7s'];
		$arquivo_grupo_produto->no_mime_type = $args['no_mime_type'];
		$arquivo_grupo_produto->no_url_origem = $args['no_url_origem'];

        if ($arquivo_grupo_produto->update()) {
            return $this;
        } else {
            return;
        }
    }
}
