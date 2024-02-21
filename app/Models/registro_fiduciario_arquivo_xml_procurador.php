<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_arquivo_xml_procurador extends Model
{
    protected $table = 'registro_fiduciario_arquivo_xml_procurador';
    protected $primaryKey = 'id_registro_fiduciario_arquivo_xml_procurador';
    public $timestamps = false;

    // Funções especiais
    public function insere($args) 
    {
        $this->no_procurador = $args['no_procurador'];
        $this->no_nacionalidade = $args['no_nacionalidade'];
        $this->no_profissao = $args['no_profissao'];
        $this->no_tipo_documento = $args['no_tipo_documento'];
        $this->numero_documento = $args['numero_documento'];
        $this->no_orgao_expedidor_documento = $args['no_orgao_expedidor_documento'];
        $this->uf_orgao_expedidor_documento = $args['uf_orgao_expedidor_documento'];
        $this->dt_expedicao_documento = $args['dt_expedicao_documento'];
        $this->tp_pessoa = $args['tp_pessoa'];
        $this->nu_cpf_cnpj = $args['nu_cpf_cnpj'];
        $this->no_endereco = $args['no_endereco'];
        $this->no_estado_civil = $args['no_estado_civil'];
        $this->id_usuario_cad = Auth::User()->id_usuario;
        // Novos campos para contato de cada procurador
        $this->nu_telefone_contato = (isset($args['nu_telefone_contato'])?$args['nu_telefone_contato']:NULL);
        $this->no_email_contato = (isset($args['no_email_contato'])?$args['no_email_contato']:NULL);
       
        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}

