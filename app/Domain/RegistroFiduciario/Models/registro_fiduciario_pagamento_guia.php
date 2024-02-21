<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_pagamento_guia extends Model
{
    protected $table = 'registro_fiduciario_pagamento_guia';
    protected $primaryKey = 'id_registro_fiduciario_pagamento_guia';
    public $timestamps = false;

    public function registro_fiduciario_pagamento()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento', 'id_registro_fiduciario_pagamento');
    }
    public function arquivo_grupo_produto_guia()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto_guia', 'id_arquivo_grupo_produto');
    }
    public function arquivo_grupo_produto_comprovante()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto_comprovante', 'id_arquivo_grupo_produto');
    }
    public function arisp_boleto()
    {
        return $this->belongsTo('App\Domain\Arisp\Models\arisp_boleto', 'id_arisp_boleto');
    }
}
