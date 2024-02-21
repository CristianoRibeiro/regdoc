<?php

namespace App\Domain\Portal\Models;

use Illuminate\Database\Eloquent\Model;

class portal_certificado_vidaas_cliente extends Model
{
    protected $table = 'portal_certificado_vidaas_cliente';
    protected $primaryKey = 'id_portal_certificado_vidaas_cliente';
    public $timestamps = false;

	// Funções de relacionamento
    public function usuario_cad()
    {
        return $this->belongsTo(usuario::class, 'id_usuario_cad');
    }
}
