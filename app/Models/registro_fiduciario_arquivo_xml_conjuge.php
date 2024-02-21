<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_arquivo_xml_conjuge extends Model
{
    protected $table = 'registro_fiduciario_arquivo_xml_conjuge';
    protected $primaryKey = 'id_registro_fiduciario_arquivo_xml_conjuge';
    public $timestamps = false;

    // Funções especiais
    public function insere($args) 
    {
        $this->no_conjuge = $args['no_conjuge'];
	    $this->no_nacionalidade = $args['no_nacionalidade'];
	    $this->no_profissao = $args['no_profissao'];
	    $this->no_tipo_documento = $args['no_tipo_documento'];
	    $this->numero_documento = $args['numero_documento'];
	    $this->no_orgao_expedidor_documento = $args['no_orgao_expedidor_documento'];
	    $this->uf_orgao_expedidor_documento = $args['uf_orgao_expedidor_documento'];
	    $this->dt_expedicao_documento = $args['dt_expedicao_documento'];
	    $this->nu_cpf = $args['nu_cpf'];
	    $this->no_endereco = $args['no_endereco'];
	    $this->id_usuario_cad = Auth::User()->id_usuario;
       
        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}

