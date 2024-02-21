<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_arquivo_xml_parte extends Model
{
    protected $table = 'registro_fiduciario_arquivo_xml_parte';
    protected $primaryKey = 'id_registro_fiduciario_arquivo_xml_parte';
    public $timestamps = false;

    // Funções especiais
    public function insere($args) 
    {
        $this->id_registro_fiduciario_arquivo_xml = $args['id_registro_fiduciario_arquivo_xml'];
        $this->id_tipo_parte_registro_fiduciario = $args['id_tipo_parte_registro_fiduciario'];
        $this->id_registro_fiduciario_arquivo_xml_conjuge = $args['id_registro_fiduciario_arquivo_xml_conjuge'];
        $this->id_registro_fiduciario_arquivo_xml_procurador = $args['id_registro_fiduciario_arquivo_xml_procurador'];
        $this->no_parte = $args['no_parte'];
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
        $this->no_regime_bens = $args['no_regime_bens'];
        $this->id_usuario_cad = Auth::User()->id_usuario;

        //Novos campos 24 de agosto 2018
        $this->tp_sexo = $args['tp_sexo'];
        $this->no_bairro = $args['no_bairro'];
        $this->no_cidade_endereco = $args['no_cidade_endereco'];
        $this->uf_endereco = $args['uf_endereco'];
        $this->no_pais_endereco = $args['no_pais_endereco'];
//        $this->nu_telefone_contato = $args['nu_telefone_contato'];
//        $this->no_email_contato = $args['no_email_contato'];




        // Novos campos para contato de cada parte
        $this->nu_telefone_contato = (isset($args['nu_telefone_contato'])?$args['nu_telefone_contato']:NULL);
        $this->no_email_contato = (isset($args['no_email_contato'])?$args['no_email_contato']:NULL);
       
        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}

