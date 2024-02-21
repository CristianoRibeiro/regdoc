<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class monitor extends Model
{
    protected $table = 'monitor';
    protected $primaryKey = 'id_monitor';
    public $timestamps = false;
    protected $guarded  = array();

    // Funções de relacionamento
    public function tipo_monitor() {
        return $this->belongsTo(tipo_monitor::class,'id_tipo_monitor');
    }

}