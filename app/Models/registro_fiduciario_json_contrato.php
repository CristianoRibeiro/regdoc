<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon\Carbon;

class registro_fiduciario_json_contrato extends Model
{
	
	protected $table = 'registro_fiduciario_json_contrato';
	protected $primaryKey = 'id_registro_fiduciario_json_contrato';
    public $timestamps = false;
	
	// Funções de relacionamento
	public function usuario() 
	{
		return $this->belongsTo(usuario::class,'id_usuario');
	}

	// Funções especiais
	public function insere($args) 
    {
        $this->where('id_usuario', Auth::User()->id_usuario)->where('tp_conveniencia', $args['tp_conveniencia'])->delete();

        $this->id_usuario = Auth::User()->id_usuario;
		$this->de_json = $args['de_json'];
		$this->tp_conveniencia = $args['tp_conveniencia'];

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }

}
