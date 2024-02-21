<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class portal_certificado_vidaas extends Model
{
    protected $table = 'portal_certificado_vidaas';
    protected $primaryKey = 'id_portal_certificado_vidaas';
    public $timestamps = false;

	// Funções de relacionamento
    public function cidade() {
        return $this->belongsTo(cidade::class,'id_cidade');
    }
}
