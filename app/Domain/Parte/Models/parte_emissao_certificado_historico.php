<?php

namespace App\Domain\Parte\Models;

use App\Domain\Usuario\Models\usuario;

use Illuminate\Database\Eloquent\Model;

class parte_emissao_certificado_historico extends Model
{
	protected $table = 'parte_emissao_certificado_historico';
	protected $primaryKey = 'id_parte_emissao_certificado_historico';
	public $timestamps = false;

	// Funções de relacionamento
	public function parte_emissao_certificado_situacao()
	{
		return $this->belongsTo(parte_emissao_certificado_situacao::class, 'id_parte_emissao_certificado_situacao');
	}

	public function usuario()
	{
		return $this->belongsTo(usuario::class, 'id_usuario_cad');
	}
}
