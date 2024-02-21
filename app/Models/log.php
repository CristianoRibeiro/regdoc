<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon\Carbon;

class log extends Model
{
	protected $table = 'log';
	protected $primaryKey = 'id_log';
    public $timestamps = false;

    // Funções de relacionamento
    public function usuario() {
        return $this->belongsTo(usuario::class, 'id_usuario');
    }
    public function detalhe() {
        return $this->hasOne(log_detalhe::class, 'id_log');
    }

    // Funções especiais
    public function insere($args) {
        $this->id_sistema = $args['id_sistema'];
        $this->id_usuario = $args['id_usuario'];
        $this->id_tipo_log = $args['id_tipo_log'];
        $this->id_modulo = (isset($args['id_modulo'])?$args['id_modulo']:NULL);
        $this->dt_log = Carbon::now();
        $this->de_log = $args['de_log'];
        $this->de_funcionalidade = (isset($args['de_funcionalidade'])?$args['de_funcionalidade']:NULL);
        $this->in_possui_controle_processo = (isset($args['in_possui_controle_processo'])?$args['in_possui_controle_processo']:NULL);
        $this->in_detalhe_log = $args['in_detalhe_log'];
        $this->no_link_acesso = (isset($args['no_link_acesso'])?$args['no_link_acesso']:NULL);
        $this->no_endereco_ip = (isset($args['no_endereco_ip'])?$args['no_endereco_ip']:NULL);
        $this->no_endereco_dns = (isset($args['no_endereco_dns'])?$args['no_endereco_dns']:NULL);
        $this->no_tabela = (isset($args['no_tabela'])?$args['no_tabela']:NULL);
        $this->no_formulario = (isset($args['no_formulario'])?$args['no_formulario']:NULL);
        $this->no_interface = (isset($args['no_interface'])?$args['no_interface']:NULL);
        $this->id_usuario_cad = (Auth::check()?Auth::User()->id_usuario:$args['id_usuario']);
        $this->dt_cadastro = Carbon::now();

        if ($this->save()) {
            return $this;
        } else {
            return;
        }
    }
}
