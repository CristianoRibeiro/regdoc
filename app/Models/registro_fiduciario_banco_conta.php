<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_banco_conta extends Model
{
    protected $table = 'registro_fiduciario_operacao_banco_conta';
    protected $primaryKey = 'id_registro_fiduciario_operacao_banco_conta';
    public $timestamps = false;

    // Funções especiais
    public function insere($args) 
    {
        $this->id_banco             = $args['id_banco'];
        $this->nu_agencia           = $args['nu_agencia'];
        $this->nu_dv_agencia        = $args['nu_dv_agencia'];
        $this->nu_conta             = $args['nu_conta'];
        $this->nu_dv_conta          = $args['nu_dv_conta'];
        $this->tipo_conta           = $args['tipo_conta'];
        $this->nu_variacao          = $args['nu_variacao'];
        $this->in_conta_devolucao   = $args['in_conta_devolucao'];
        $this->nu_ordem             = $args['nu_ordem'];
        $this->in_tipo_pessoa_conta = $args['in_tipo_pessoa_conta'];
        $this->nu_cpf_cnpj_conta    = $args['nu_cpf_cnpj_conta'];
        $this->no_pessoa_conta      = $args['no_pessoa_conta'];
        $this->id_usuario_cad       = Auth::User()->id_usuario;
        
        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }

    // Funções de relacionamento
    public function operacao()
    {
        return $this->belongsTo(registro_fiduciario_operacao::class,'id_registro_fiduciario_operacao');
    }

}