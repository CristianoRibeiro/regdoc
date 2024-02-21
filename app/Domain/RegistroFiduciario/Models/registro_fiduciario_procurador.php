<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_procurador extends Model
{
    protected $table = 'registro_fiduciario_procurador';
    protected $primaryKey = 'id_registro_fiduciario_procurador';
    public $timestamps = false;

    public function pedido_usuario() {
        return $this->belongsTo('App\Domain\Pedido\Models\pedido_usuario', 'id_pedido_usuario');
    }
    public function registro_fiduciario_parte() {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte', 'id_registro_fiduciario_parte');
    }
    public function parte_emissao_certificado()
    {
        return $this->hasOne('App\Domain\Parte\Models\parte_emissao_certificado', 'nu_cpf_cnpj', 'nu_cpf_cnpj');
    }
    public function registro_fiduciario_parte_assinatura()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_assinatura', 'id_registro_fiduciario_procurador');
    }
    public function registro_fiduciario_parte_assinatura_na_ordem()
    {
        return $this->hasMany('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura', 'id_registro_fiduciario_procurador')
            ->join('registro_fiduciario_assinatura', function($join) {
                $join->on('registro_fiduciario_assinatura.id_registro_fiduciario_assinatura', '=', 'registro_fiduciario_parte_assinatura.id_registro_fiduciario_assinatura')
                    ->whereRaw('(registro_fiduciario_assinatura.nu_ordem_assinatura_atual = registro_fiduciario_parte_assinatura.nu_ordem_assinatura OR registro_fiduciario_assinatura.in_ordem_assinatura=\'N\')');
            });
    }
    public function cidade()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade');
    }
}
