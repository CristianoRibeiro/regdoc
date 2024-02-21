<?php

namespace App\Domain\Parte\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\Estado\Models\cidade;
use App\Domain\Pedido\Models\pedido;
use App\Domain\Portal\Models\portal_certificado_vidaas;

class parte_emissao_certificado extends Model
{
	protected $table = 'parte_emissao_certificado';
	protected $primaryKey = 'id_parte_emissao_certificado';
	public $timestamps = false;

	// Funções de relacionamento
	public function pedido()
	{
		return $this->belongsTo(pedido::class,'id_pedido');
	}
	public function parte_emissao_certificado_situacao()
	{
		return $this->belongsTo(parte_emissao_certificado_situacao::class, 'id_parte_emissao_certificado_situacao');
	}
	public function parte_emissao_certificado_tipo()
	{
		return $this->belongsTo(parte_emissao_certificado_tipo::class, 'id_parte_emissao_certificado_tipo');
	}
	public function portal_certificado_vidaas()
	{
		return $this->belongsTo(portal_certificado_vidaas::class, 'id_portal_certificado_vidaas');
	}
	public function cidade()
    {
        return $this->belongsTo(cidade::class, 'id_cidade');
    }
	public function parte_emissao_certificado_historico()
	{
		return $this->hasMany(parte_emissao_certificado_historico::class, 'id_parte_emissao_certificado');
	}
}
