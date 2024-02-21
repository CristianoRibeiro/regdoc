<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_nota_devolutiva_arquivo_grupo extends Model
{
    protected $table = 'registro_fiduciario_nota_devolutiva_arquivo_grupo';
    protected $primaryKey = 'id_registro_fiduciario_nota_devolutiva_arquivo_grupo';
    public $timestamps = false;

    public function registro_fiduciario_nota_devolutiva()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva', 'id_registro_fiduciario_nota_devolutiva');
    }
    public function arquivo_grupo()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto');
    }
}
