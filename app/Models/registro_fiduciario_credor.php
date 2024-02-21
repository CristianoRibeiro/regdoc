<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_credor extends Model
{
    protected $table = 'registro_fiduciario_credor';
    protected $primaryKey = 'id_registro_fiduciario_credor';
    public $timestamps = false;

    public function agencia()
    {
        return $this->belongsTo(agencia::class,'id_agencia');
    }
    public function cidade()
    {
        return $this->belongsTo(cidade::class,'id_cidade');
    }

    // Funções especiais
    public function insere($args)
    {
        $this->id_agencia = $args['id_agencia'];
        $this->nu_cpf_cnpj = $args['nu_cpf_cnpj'];
        $this->no_credor = $args['no_credor'];
		$this->id_usuario_cad = Auth::User()->id_usuario;

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}
