<?php
namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_parte_assinatura_arquivo extends Model
{
    protected $table = 'registro_fiduciario_parte_assinatura_arquivo';
    protected $primaryKey = 'id_registro_fiduciario_parte_assinatura_arquivo';
    public $timestamps = false;

    public function arquivo_grupo_produto()
    {
        return $this->belongsTo(arquivo_grupo_produto::class,'id_arquivo_grupo_produto');
    }
    public function registro_fiduciario_parte_assinatura()
    {
        return $this->belongsTo(registro_fiduciario_parte_assinatura::class,'id_registro_fiduciario_parte_assinatura');
    }

}
