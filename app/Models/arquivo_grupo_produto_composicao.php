<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class arquivo_grupo_produto_composicao extends Model 
{
	protected $table = 'arquivo_grupo_produto_composicao';
	protected $primaryKey = 'id_arquivo_grupo_produto_composicao';
    public $timestamps = false;

    // Funções especiais
    public function insere($args) {
		$this->id_arquivo_grupo_produto = $args['id_arquivo_grupo_produto'];
		$this->no_arquivo = $args['no_arquivo'];
		$this->no_local_arquivo = $args['no_local_arquivo'];
		$this->no_extensao = $args['no_extensao'];
		$this->nu_tamanho_kb = $args['nu_tamanho_kb'];
		$this->no_hash = $args['no_hash'];
		$this->id_usuario_cad = Auth::User()->id_usuario;
        
        if ($this->save()) {
        	return $this;
        } else {
        	return false;
        }
    }
}
