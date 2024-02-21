<?php
namespace App\Domain\Usuario\Models;

use Illuminate\Database\Eloquent\Model;

class usuario_certificado extends Model
{
	protected $table = 'usuario_certificado';
	protected $primaryKey = 'id_usuario_certificado';
    public $timestamps = false;

	// Funções de relacionamento
	public function usuario()
	{
		return $this->belongsTo('App\Usuario\Models\usuario', 'id_usuario');
	}
}
