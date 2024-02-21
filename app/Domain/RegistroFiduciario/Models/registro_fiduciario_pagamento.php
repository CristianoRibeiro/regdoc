<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_pagamento extends Model
{
    protected $table = 'registro_fiduciario_pagamento';
    protected $primaryKey = 'id_registro_fiduciario_pagamento';
    public $timestamps = false;

    public function registro_fiduciario_pagamento_situacao()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_situacao', 'id_registro_fiduciario_pagamento_situacao');
    }
    public function registro_fiduciario_pagamento_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_tipo', 'id_registro_fiduciario_pagamento_tipo');
    }
    public function registro_fiduciario_pagamento_guia()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_guia', 'id_registro_fiduciario_pagamento');
    }
    public function registro_fiduciario_pagamento_historico()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_historico', 'id_registro_fiduciario_pagamento');
    }
    public function registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario', 'id_registro_fiduciario');
    }
    public function arquivo_grupo_produto()
    {
        return $this->belongsTo('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'id_arquivo_grupo_produto_isencao', 'id_arquivo_grupo_produto');
    }
}
