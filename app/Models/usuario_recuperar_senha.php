<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usuario_recuperar_senha extends Model 
{
	
	protected $table = 'usuario_recuperar_senha';
	protected $primaryKey = 'id_usuario_recuperar_senha';
    public $timestamps = false;
	
	public function usuario() 
	{
		return $this->belongsTo(usuario::class,'id_usuario');
	}
}
