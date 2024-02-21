<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_parte extends Model
{
    protected $table = 'registro_fiduciario_parte';
    protected $primaryKey = 'id_registro_fiduciario_parte';
    public $timestamps = false;

    public function registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_procurador()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_procurador', 'id_registro_fiduciario_parte');
    }
    public function registro_fiduciario_parte_conjuge()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte', 'id_registro_fiduciario_parte_conjuge');
    }
    public function pedido_usuario()
    {
        return $this->belongsTo('App\Domain\Pedido\Models\pedido_usuario', 'id_pedido_usuario');
    }
    public function tipo_parte_registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\tipo_parte_registro_fiduciario', 'id_tipo_parte_registro_fiduciario');
    }
    public function registro_fiduciario_parte_assinatura()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_assinatura', 'id_registro_fiduciario_parte');
    }
    public function registro_fiduciario_parte_assinatura_na_ordem()
    {
        return $this->hasMany('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura', 'id_registro_fiduciario_parte')
            ->join('registro_fiduciario_assinatura', function($join) {
                $join->on('registro_fiduciario_assinatura.id_registro_fiduciario_assinatura', '=', 'registro_fiduciario_parte_assinatura.id_registro_fiduciario_assinatura')
                    ->whereRaw('(registro_fiduciario_assinatura.nu_ordem_assinatura_atual = registro_fiduciario_parte_assinatura.nu_ordem_assinatura OR registro_fiduciario_assinatura.in_ordem_assinatura=\'N\')');
            });
    }
    public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'registro_fiduciario_parte_arquivo_grupo', 'id_registro_fiduciario_parte', 'id_arquivo_grupo_produto')->wherePivot('in_registro_ativo', 'S');
    }
    public function registro_fiduciario_parte_arquivo_grupo()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_arquivo_grupo', 'id_registro_fiduciario_parte')->where('in_registro_ativo', 'S');
    }
    public function registro_fiduciario_parte_capacidade_civil()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_capacidade_civil', 'id_registro_fiduciario_parte_capacidade_civil');
    }
    public function registro_fiduciario_parte_tipo_instrumento()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_tipo_instrumento', 'id_registro_fiduciario_parte_tipo_instrumento');
    }
    public function registro_fiduciario_verificacoes_parte()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_verificacoes_parte', 'id_registro_fiduciario_parte');
    }
    public function parte_emissao_certificado()
    {
        return $this->hasOne('App\Domain\Parte\Models\parte_emissao_certificado', 'nu_cpf_cnpj', 'nu_cpf_cnpj');
    }
    public function cidade()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade');
    }
    public function procuracao()
    {
        return $this->belongsTo('App\Domain\Procuracao\Models\procuracao', 'id_procuracao');
    }
    public function registro_tipo_parte_tipo_pessoa()
    {
        return $this->belongsTo('App\Domain\Registro\Models\registro_tipo_parte_tipo_pessoa', 'id_registro_tipo_parte_tipo_pessoa');
    }
}
