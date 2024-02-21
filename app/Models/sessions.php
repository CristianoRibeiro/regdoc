<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class sessions extends Model
{
	protected $table = 'sessions';
	protected $primaryKey = 'id';
    public $timestamps = false;
	
	// Funções de relacionamento
	public function usuario() {
		return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'user_id', 'id_usuario');
	}

	// Atributos
	public function getDtUltimaAtividadeAttribute()
    {
        return Carbon::parse($this->last_activity)->setTimezone(config('app.timezone'));
    }
	public function getInExpiradoAttribute()
    {
        return $this->dt_ultima_atividade < Carbon::now()->subMinutes(config('session.lifetime'));
    }
}
