<?php
namespace App\Domain\RegistroFiduciarioAssinatura\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_assinatura extends Model
{
    protected $table = 'registro_fiduciario_assinatura';
    protected $primaryKey = 'id_registro_fiduciario_assinatura';
    public $timestamps = false;

    public function registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario','id_registro_fiduciario');
    }
    public function registro_fiduciario_assinatura_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_assinatura_tipo','id_registro_fiduciario_assinatura_tipo');
    }
    public function registro_fiduciario_parte_assinatura()
    {
        return $this->hasMany('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura','id_registro_fiduciario_assinatura')->orderBy('nu_ordem_assinatura', 'ASC');
    }
    public function registro_fiduciario_parte_assinatura_arquivos()
    {
        return $this->hasManyThrough('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura_arquivo', 'App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura', 'id_registro_fiduciario_assinatura', 'id_registro_fiduciario_parte_assinatura');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario','id_usuario_cad','id_usuario');
    }
}
