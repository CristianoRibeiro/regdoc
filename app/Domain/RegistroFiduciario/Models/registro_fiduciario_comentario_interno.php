<?php

namespace App\Domain\RegistroFiduciario\Models;

use App\Domain\Usuario\Models\usuario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class registro_fiduciario_comentario_interno extends Model
{
  protected $table = 'registro_fiduciario_comentario_interno';

  protected $primaryKey = 'id_registro_fiduciario_comentario_interno';

  public $timestamps = false;

  public function registro_fiduciario(): BelongsTo
  {
    return $this->belongsTo(registro_fiduciario::class, 'id_registro_fiduciario');
  }

  public function usuario(): BelongsTo
  {
    return $this->belongsTo(usuario::class, 'id_usuario_cad');
  }
}