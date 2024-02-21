<?php

namespace App\Domain\CanaisPdv\Models;

use Illuminate\Database\Eloquent\Model;

class canal_pdv_parceiro extends Model
{
	protected $table = 'canal_pdv_parceiro';
	protected $primaryKey = 'id_canal_pdv_parceiro';
	public $timestamps = false;

	public function usuario_cad() {
        return $this->belongsTo(usuario::class, 'id_usuario_cad', 'id_usuario');
    }
}
