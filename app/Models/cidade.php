<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cidade extends Model
{
    protected $table = 'cidade';
    protected $primaryKey = 'id_cidade';
    public $timestamps = false;
    protected $guarded  = array();

	// Funções de relacionamento
	public function estado() {
		return $this->belongsTo(estado::class,'id_estado');
	}

    public function endereco() {
        return $this->hasOne('App\endereco','id_cidade');
    }
}