<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario extends Model
{
    protected $table = 'registro_fiduciario';
    protected $primaryKey = 'id_registro_fiduciario';
    public $timestamps = false;

    // Funções de relacionamento
    public function registro_fiduciario_pedido()
    {
        return $this->hasOne('App\Domain\RegistroFiduciario\Models\registro_fiduciario_pedido', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_parte()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_partes()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_operacao()
    {
        return $this->hasOne('App\Domain\RegistroFiduciario\Models\registro_fiduciario_operacao', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_endereco()
    {
        return $this->hasOne('App\Domain\RegistroFiduciario\Models\registro_fiduciario_endereco', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo', 'id_registro_fiduciario_tipo');
    }
    public function cidade_emissao_contrato()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade_emissao_contrato');
    }
    public function usuario()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad');
    }
    public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'registro_fiduciario_arquivo_grupo_produto', 'id_registro_fiduciario', 'id_arquivo_grupo_produto')->wherePivot('in_registro_ativo', 'S');
    }
    public function registro_fiduciario_arquivo_grupo_produto()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_arquivo_grupo_produto', 'id_registro_fiduciario')->where('in_registro_ativo', 'S');
    }
    public function serventia()
    {
        return $this->hasOne('App\Domain\Serventia\Models\serventia', 'id_serventia', 'id_serventia');
    }
    public function serventia_ri()
    {
        return $this->hasOne('App\Domain\Serventia\Models\serventia', 'id_serventia', 'id_serventia_ri');
    }
    public function serventia_nota()
    {
        return $this->hasOne('App\Domain\Serventia\Models\serventia', 'id_serventia', 'id_serventia_nota');
    }
    public function registro_fiduciario_assinaturas()
    {
        return $this->hasMany('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura', 'id_registro_fiduciario')->orderBy('id_registro_fiduciario_assinatura_tipo', 'ASC')->orderBy('dt_cadastro', 'DESC');
    }
    public function registro_fiduciario_partes_assinaturas()
    {
        return $this->hasManyThrough('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_assinatura', 'App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte', 'id_registro_fiduciario', 'id_registro_fiduciario_parte')->orderBy('nu_ordem_assinatura');
    }
    public function partes_arquivos_nao_assinados() {
        return $this->registro_fiduciario_partes_assinaturas()
                    ->join('registro_fiduciario_parte_assinatura_arquivo', function($join) {
                        $join->on('registro_fiduciario_parte_assinatura_arquivo.id_registro_fiduciario_parte_assinatura', '=', 'registro_fiduciario_parte_assinatura.id_registro_fiduciario_parte_assinatura')
                             ->whereNull('id_arquivo_grupo_produto_assinatura');
                    });
    }
    public function partes_arquivos_assinados() {
        return $this->registro_fiduciario_partes_assinaturas()
                    ->join('registro_fiduciario_parte_assinatura_arquivo', function($join) {
                        $join->on('registro_fiduciario_parte_assinatura_arquivo.id_registro_fiduciario_parte_assinatura', '=', 'registro_fiduciario_parte_assinatura.id_registro_fiduciario_parte_assinatura')
                             ->whereNotNull('id_arquivo_grupo_produto_assinatura');
                    });
    }
    public function registro_fiduciario_natureza()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_natureza', 'id_registro_fiduciario_natureza');
    }
    public function registro_fiduciario_imovel_livro()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel_livro', 'id_registro_fiduciario_imovel_livro');
    }
    public function registro_fiduciario_imovel_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel_tipo', 'id_registro_fiduciario_imovel_tipo');
    }
    public function registro_fiduciario_cedula()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_cedula', 'id_registro_fiduciario_cedula');
    }
    public function registro_fiduciario_impostotransmissao()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_impostotransmissao', 'id_registro_fiduciario_impostotransmissao');
    }
    public function registro_fiduciario_dajes()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_dajes', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_apresentante()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_apresentante', 'id_registro_fiduciario_apresentante');
    }
    public function registro_fiduciario_custodiante()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_custodiante', 'id_registro_fiduciario_custodiante');
    }
    public function registro_fiduciario_verificacoes_imovel()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_verificacoes_imovel', 'id_registro_fiduciario');
    }
    public function arquivos_partes()
    {
        return $this->hasManyThrough('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_arquivo_grupo', 'App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte', 'id_registro_fiduciario', 'id_registro_fiduciario_parte');
    }
    public function registro_fiduciario_imovel()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_imovel', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_pagamentos()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento', 'id_registro_fiduciario')->orderBy('dt_cadastro','DESC');
    }
    public function registro_fiduciario_comentarios()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_comentario', 'id_registro_fiduciario')->orderBy('dt_cadastro','DESC');
    }
    public function registro_fiduciario_nota_devolutivas()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva', 'id_registro_fiduciario')->orderBy('dt_cadastro','DESC');
    }
    public function registro_fiduciario_observadores()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_observador', 'id_registro_fiduciario')->orderBy('dt_cadastro','DESC');
    }
    public function integracao()
    {
        return $this->belongsTo('App\Domain\Integracao\Models\integracao', 'id_integracao');
    }
    public function registro_fiduciario_checklists()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_checklist', 'id_registro_fiduciario')->orderBy('nu_ordem', 'ASC');
    }
    public function empreendimento()
    {
        return $this->belongsTo('App\Domain\Construtora\Models\empreendimento', 'id_empreendimento');
    }
}
