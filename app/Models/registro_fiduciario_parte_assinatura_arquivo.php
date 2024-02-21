<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_parte_assinatura_arquivo extends Model
{
    protected $table = 'registro_fiduciario_parte_assinatura_arquivo';
    protected $primaryKey = 'id_registro_fiduciario_parte_assinatura_arquivo';
    public $timestamps = false;

    public function arquivo_grupo_produto()
    {
        return $this->belongsTo(arquivo_grupo_produto::class,'id_arquivo_grupo_produto');
    }
    public function registro_fiduciario_parte_assinatura()
    {
        return $this->belongsTo(registro_fiduciario_parte_assinatura::class,'id_registro_fiduciario_parte_assinatura');
    }

    /*
    // Funções especiais
    public function insere($args)
    {
        $this->id_registro_fiduciario = $args['id_registro_fiduciario'];
        $this->id_tipo_parte_registro_fiduciario = $args['id_tipo_parte_registro_fiduciario'];
        $this->id_registro_fiduciario_conjuge = $args['id_registro_fiduciario_conjuge'];
        $this->id_registro_fiduciario_procurador = $args['id_registro_fiduciario_procurador'];
        $this->no_parte = $args['no_parte'];
        $this->in_parte_master = $args['in_parte_master'];
        $this->tp_sexo = $args['tp_sexo'];
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
        $this->no_bairro = $args['no_bairro'];
        $this->no_cidade_endereco = $args['no_cidade_endereco'];
        $this->uf_endereco = $args['uf_endereco'];
        $this->no_pais_endereco = $args['no_pais_endereco'];
        $this->no_estado_civil = $args['no_estado_civil'];
        $this->no_regime_bens = $args['no_regime_bens'];
        $this->id_usuario_cad = Auth::User()->id_usuario;
        $this->id_pedido_usuario = (isset($args['id_pedido_usuario'])?$args['id_pedido_usuario']:NULL);
        $this->nu_telefone_contato = (isset($args['nu_telefone_contato'])?$args['nu_telefone_contato']:NULL);
        $this->no_email_contato = (isset($args['no_email_contato'])?$args['no_email_contato']:NULL);

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }

    public function atualiza($args)
    {
        $registro_fiduciario_parte = registro_fiduciario_parte::find($args['id_registro_fiduciario_parte']);

        $registro_fiduciario_parte->id_tipo_parte_registro_fiduciario = $args['id_tipo_parte_registro_fiduciario'];
        $registro_fiduciario_parte->id_registro_fiduciario_conjuge = $args['id_registro_fiduciario_conjuge'];
        $registro_fiduciario_parte->id_registro_fiduciario_procurador = $args['id_registro_fiduciario_procurador'];
        $registro_fiduciario_parte->no_parte = $args['no_parte'];
        $registro_fiduciario_parte->in_parte_master = $args['in_parte_master'];
        $registro_fiduciario_parte->tp_sexo = $args['tp_sexo'];
        $registro_fiduciario_parte->no_nacionalidade = $args['no_nacionalidade'];
        $registro_fiduciario_parte->no_profissao = $args['no_profissao'];
        $registro_fiduciario_parte->no_tipo_documento = $args['no_tipo_documento'];
        $registro_fiduciario_parte->numero_documento = $args['numero_documento'];
        $registro_fiduciario_parte->no_orgao_expedidor_documento = $args['no_orgao_expedidor_documento'];
        $registro_fiduciario_parte->uf_orgao_expedidor_documento = $args['uf_orgao_expedidor_documento'];
        $registro_fiduciario_parte->dt_expedicao_documento = $args['dt_expedicao_documento'];
        $registro_fiduciario_parte->tp_pessoa = $args['tp_pessoa'];
        $registro_fiduciario_parte->nu_cpf_cnpj = $args['nu_cpf_cnpj'];
        $registro_fiduciario_parte->no_endereco = $args['no_endereco'];
        $registro_fiduciario_parte->no_bairro = $args['no_bairro'];
        $registro_fiduciario_parte->no_cidade_endereco = $args['no_cidade_endereco'];
        $registro_fiduciario_parte->uf_endereco = $args['uf_endereco'];
        $registro_fiduciario_parte->no_pais_endereco = $args['no_pais_endereco'];
        $registro_fiduciario_parte->no_estado_civil = $args['no_estado_civil'];
        $registro_fiduciario_parte->no_regime_bens = $args['no_regime_bens'];
        $registro_fiduciario_parte->id_usuario_cad = Auth::User()->id_usuario;
        $registro_fiduciario_parte->id_pedido_usuario = (isset($args['id_pedido_usuario'])?$args['id_pedido_usuario']:NULL);
        $registro_fiduciario_parte->nu_telefone_contato = (isset($args['nu_telefone_contato'])?$args['nu_telefone_contato']:NULL);
        $registro_fiduciario_parte->no_email_contato = (isset($args['no_email_contato'])?$args['no_email_contato']:NULL);

        if ($registro_fiduciario_parte->update()) {
            return $this;
        } else {
            return false;
        }
    }
    */
}
