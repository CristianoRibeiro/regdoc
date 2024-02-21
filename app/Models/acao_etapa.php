<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class acao_etapa extends Model
{
    protected $table = 'acao_etapa';
    protected $primaryKey = 'id_acao_etapa';
    public $timestamps = false;

    public function resultado_acao() {
		return $this->hasMany(resultado_acao::class,'id_acao_etapa')->where('in_registro_ativo','S')->orderBy('nu_ordem');
	}
    public function acao_etapa_mensagem() {
		return $this->hasMany(acao_etapa_mensagem::class,'id_acao_etapa')->where('in_registro_ativo','S');
	}
}

