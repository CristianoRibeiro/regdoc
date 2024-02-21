<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Illuminate\Notifications\Notifiable;

class registro_fiduciario_procurador extends Model
{
    use Notifiable;
    protected $table = 'registro_fiduciario_procurador';
    protected $primaryKey = 'id_registro_fiduciario_procurador';
    public $timestamps = false;

    public function pedido_usuario() {
        return $this->belongsTo(pedido_usuario::class,'id_pedido_usuario');
    }

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
        $registro_fiduciario_procurador = registro_fiduciario_procurador::find($args['id_registro_fiduciario_procurador']);

        $registro_fiduciario_procurador->no_procurador = $args['no_procurador'];
        $registro_fiduciario_procurador->no_nacionalidade = $args['no_nacionalidade'];
        $registro_fiduciario_procurador->no_profissao = $args['no_profissao'];
        $registro_fiduciario_procurador->no_tipo_documento = $args['no_tipo_documento'];
        $registro_fiduciario_procurador->numero_documento = $args['numero_documento'];
        $registro_fiduciario_procurador->no_orgao_expedidor_documento = $args['no_orgao_expedidor_documento'];
        $registro_fiduciario_procurador->uf_orgao_expedidor_documento = $args['uf_orgao_expedidor_documento'];
        $registro_fiduciario_procurador->dt_expedicao_documento = $args['dt_expedicao_documento'];
        $registro_fiduciario_procurador->tp_pessoa = $args['tp_pessoa'];
        $registro_fiduciario_procurador->nu_cpf_cnpj = $args['nu_cpf_cnpj'];
        $registro_fiduciario_procurador->no_endereco = $args['no_endereco'];
        $registro_fiduciario_procurador->no_estado_civil = $args['no_estado_civil'];
        $registro_fiduciario_procurador->id_usuario_cad = Auth::User()->id_usuario;
        $registro_fiduciario_procurador->id_pedido_usuario = (isset($args['id_pedido_usuario'])?$args['id_pedido_usuario']:NULL);
        $registro_fiduciario_procurador->nu_telefone_contato = (isset($args['nu_telefone_contato'])?$args['nu_telefone_contato']:NULL);
        $registro_fiduciario_procurador->no_email_contato = (isset($args['no_email_contato'])?$args['no_email_contato']:NULL);

        if ($registro_fiduciario_procurador->update()) {
            return $this;
        } else {
            return false;
        }
    }
}
