<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon\Carbon;

class usuario_certificado extends Model
{

	protected $table = 'usuario_certificado';
	protected $primaryKey = 'id_usuario_certificado';
    public $timestamps = false;

	// Funções de relacionamento
	public function usuario()
	{
		return $this->belongsTo(usuario::class,'id_usuario');
	}

	// Funções especiais
	public function insere($args)
    {
        $this->id_usuario = Auth::User()->id_usuario;
		$this->no_comum = $args['no_comum'];
		$this->no_email = (isset($args['no_email'])?$args['no_email']:NULL);
		$this->no_autoridade_raiz = $args['no_autoridade_raiz'];
		$this->no_autoridade_unidade = $args['no_autoridade_unidade'];
		$this->no_autoridade_certificadora = $args['no_autoridade_certificadora'];
		$this->nu_serial = $args['nu_serial'];
		$this->dt_validade_ini = Carbon::parse($args['dt_validade_ini']);
		$this->dt_validade_fim = Carbon::parse($args['dt_validade_fim']);
		$this->tp_certificado = $args['tp_certificado'];
		$this->nu_cpf_cnpj = $args['nu_cpf_cnpj'];
		$this->no_responsavel = $args['no_responsavel'];
		$this->dt_nascimento = (isset($args['dt_nascimento'])?Carbon::parse($args['dt_nascimento']):NULL);
		$this->nu_rg = (isset($args['nu_rg'])?$args['nu_rg']:NULL);
		$this->nu_rgemissor = (isset($args['nu_rgemissor'])?$args['nu_rgemissor']:NULL);
		$this->nu_rguf = (isset($args['nu_rguf'])?$args['nu_rguf']:NULL);
		$this->de_campos = (isset($args['de_campos'])?$args['de_campos']:NULL);

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}
