<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class resultado_acao extends Model
{
    protected $table = 'resultado_acao';
    protected $primaryKey = 'id_resultado_acao';
    public $timestamps = false;

    // Funções de relacionamento
    public function acao_etapa() 
    {
		return $this->belongsTo(acao_etapa::class,'id_acao_etapa');
	}
	public function nova_fase_grupo_produto() {
		return $this->belongsTo(fase_grupo_produto::class,'id_nova_fase_grupo_produto','id_fase_grupo_produto');
	}
	public function nova_etapa_fase() {
		return $this->belongsTo(etapa_fase::class,'id_nova_etapa_fase','id_etapa_fase');
	}
	public function nova_acao_etapa() {
		return $this->belongsTo(acao_etapa::class,'id_nova_acao_etapa','id_acao_etapa');
	}
    public function resultado_acao_mensagem() {
		return $this->hasMany(resultado_acao_mensagem::class,'id_resultado_acao')->where('in_registro_ativo','S');
	}
}

