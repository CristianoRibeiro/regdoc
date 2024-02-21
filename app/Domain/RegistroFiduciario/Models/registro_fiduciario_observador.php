<?php

namespace App\Domain\RegistroFiduciario\Models;

use App\Domain\Usuario\Models\usuario;
use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_observador extends Model
{
    protected $table = 'registro_fiduciario_observador';
    protected $primaryKey = 'id_registro_fiduciario_observador';
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registro_fiduciario()
    {
        return $this->belongsTo(registro_fiduciario::class, 'id_registro_fiduciario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(usuario::class, 'id_usuario_cad');
    }
}
