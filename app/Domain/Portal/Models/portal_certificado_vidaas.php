<?php

namespace App\Domain\Portal\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\Estado\Models\cidade;

class portal_certificado_vidaas extends Model
{
    protected $table = 'portal_certificado_vidaas';

    protected $primaryKey = 'id_portal_certificado_vidaas';

    public $timestamps = false;

    // Funções de relacionamento
    public function cidade() {
        return $this->belongsTo(cidade::class,'id_cidade');
    }
    public function portal_certificado_vidaas_cliente() {
        return $this->belongsTo(portal_certificado_vidaas_cliente::class, 'id_portal_certificado_vidaas_cliente');
    }
}
