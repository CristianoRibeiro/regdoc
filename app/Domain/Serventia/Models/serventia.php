<?php
namespace App\Domain\Serventia\Models;

use Illuminate\Database\Eloquent\Model;

class serventia extends Model
{
	protected $table = 'serventia';
	protected $primaryKey = 'id_serventia';
    public $timestamps = false;

	// Funções de relacionamento
	public function serventia_cliente()
	{
		return $this->hasOne('App\Models\serventia_cliente', 'id_serventia');
	}
	public function pessoa()
	{
		return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
	}
	public function unidade_gestora()
	{
		return $this->belongsTo('App\Models\unidade_gestora', 'id_unidade_gestora');
	}
	public function tipo_serventia()
	{
		return $this->belongsTo('App\Models\tipo_serventia', 'id_tipo_serventia');
	}
}
