<?php
namespace App\Domain\Documento\Parte\Models;

use Illuminate\Database\Eloquent\Model;

class documento_procurador extends Model
{
    protected $table = 'documento_procurador';
    protected $primaryKey = 'id_documento_procurador';
    public $timestamps = false;
    protected $guarded  = array();

    public function documento_parte()
    {
        return $this->belongsTo('App\Domain\Documento\Parte\Models\documento_parte', 'id_documento_parte');
    }
    public function documento_parte_assinatura()
    {
        return $this->hasMany('App\Domain\Documento\Assinatura\Models\documento_parte_assinatura', 'id_documento_procurador');
    }
    public function documento_parte_assinatura_na_ordem()
    {
        return $this->hasMany('App\Domain\Documento\Assinatura\Models\documento_parte_assinatura', 'id_documento_procurador')
            ->join('documento_assinatura', function($join) {
                $join->on('documento_assinatura.id_documento_assinatura', '=', 'documento_parte_assinatura.id_documento_assinatura')
                    ->whereRaw('(documento_assinatura.nu_ordem_assinatura_atual = documento_parte_assinatura.nu_ordem_assinatura OR documento_assinatura.in_ordem_assinatura=\'N\')');
            });
    }
    public function parte_emissao_certificado()
    {
        return $this->hasOne('App\Domain\Parte\Models\parte_emissao_certificado', 'nu_cpf_cnpj', 'nu_cpf_cnpj');
    }
    public function cidade()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade');
    }
    public function estado_civil()
    {
        return $this->belongsTo('App\Domain\Apoio\EstadoCivil\Models\estado_civil', 'id_estado_civil');
    }
    public function nacionalidade()
    {
        return $this->belongsTo('App\Domain\Apoio\Nacionalidade\Models\nacionalidade', 'id_nacionalidade');
    }
    public function pedido_usuario()
    {
        return $this->belongsTo('App\Domain\Pedido\Models\pedido_usuario', 'id_pedido_usuario');
    }
}
