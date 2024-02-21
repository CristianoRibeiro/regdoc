<?php
namespace App\Domain\RegistroFiduciarioAssinatura\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_assinatura_tipo extends Model
{
    protected $table = 'registro_fiduciario_assinatura_tipo';
    protected $primaryKey = 'id_registro_fiduciario_assinatura_tipo';
    public $timestamps = false;
}
