<?php
namespace App\Domain\RegistroFiduciarioAssinatura\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_parte_assinatura_arquivo extends Model
{
    protected $table = 'registro_fiduciario_parte_assinatura_arquivo';
    protected $primaryKey = 'id_registro_fiduciario_parte_assinatura_arquivo';
    public $timestamps = false;

    public function arquivo_grupo_produto()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto','id_arquivo_grupo_produto');
    }
    public function arquivo_grupo_produto_assinatura()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto_assinatura','id_arquivo_grupo_produto_assinatura');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario','id_usuario_cad','id_usuario');
    }
    public function registro_fiduciario_parte_assinatura()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciarioAssinatura\Models\registro_fiduciario_parte_assinatura','id_registro_fiduciario_parte_assinatura');
    }


}
