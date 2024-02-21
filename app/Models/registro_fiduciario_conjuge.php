<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_conjuge extends Model
{
    protected $table = 'registro_fiduciario_conjuge';
    protected $primaryKey = 'id_registro_fiduciario_conjuge';
    public $timestamps = false;

    public function pedido_usuario() {
        return $this->belongsTo(pedido_usuario::class,'id_pedido_usuario');
    }

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
        $this->nu_telefone_contato = (isset($args['nu_telefone_contato'])?$args['nu_telefone_contato']:NULL);
        $this->no_email_contato = (isset($args['no_email_contato'])?$args['no_email_contato']:NULL);
        $this->id_usuario_cad = Auth::User()->id_usuario;

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }

    public function atualiza($args)
    {
        $registro_fiduciario_conjuge = registro_fiduciario_conjuge::find($args['id_registro_fiduciario_conjuge']);

        $registro_fiduciario_conjuge->no_conjuge = $args['no_conjuge'];
        $registro_fiduciario_conjuge->no_nacionalidade = $args['no_nacionalidade'];
        $registro_fiduciario_conjuge->no_profissao = $args['no_profissao'];
        $registro_fiduciario_conjuge->no_tipo_documento = $args['no_tipo_documento'];
        $registro_fiduciario_conjuge->numero_documento = $args['numero_documento'];
        $registro_fiduciario_conjuge->no_orgao_expedidor_documento = $args['no_orgao_expedidor_documento'];
        $registro_fiduciario_conjuge->uf_orgao_expedidor_documento = $args['uf_orgao_expedidor_documento'];
        $registro_fiduciario_conjuge->dt_expedicao_documento = $args['dt_expedicao_documento'];
        $registro_fiduciario_conjuge->nu_cpf = $args['nu_cpf'];
        $registro_fiduciario_conjuge->no_endereco = $args['no_endereco'];
        $registro_fiduciario_conjuge->nu_telefone_contato = (isset($args['nu_telefone_contato'])?$args['nu_telefone_contato']:NULL);
        $registro_fiduciario_conjuge->no_email_contato = (isset($args['no_email_contato'])?$args['no_email_contato']:NULL);
        $registro_fiduciario_conjuge->id_usuario_cad = Auth::User()->id_usuario;

        if ($registro_fiduciario_conjuge->update()) {
            return $this;
        } else {
            return false;
        }
    }
}
