<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class telefone extends Model 
{
	protected $table = 'telefone';
	protected $primaryKey = 'id_telefone';
    public $timestamps = false;
	
	// Funções de relacionamento
    public function tipo_telefone() {
        return $this->belongsTo(tipo_telefone::class,'id_tipo_telefone');
    }

	// Funções especiais
    public function insere($args) 
    {
        $this->id_tipo_telefone = $args['id_tipo_telefone'];
		$this->id_classificacao_telefone = $args['id_classificacao_telefone'];
		$this->nu_ddi = $args['nu_ddi'];
		$this->nu_ddd = $args['nu_ddd'];
		$this->nu_telefone = $args['nu_telefone'];
		$this->in_registro_ativo = (isset($args['in_registro_ativo'])?$args['in_registro_ativo']:'S');

        if ($this->save()) {
        	return $this;
        } else {
        	return false;
        }
    }
}
