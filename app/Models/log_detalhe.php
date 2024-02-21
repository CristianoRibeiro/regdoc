<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon\Carbon;

class log_detalhe extends Model 
{
	protected $table = 'log_detalhe';
	protected $primaryKey = 'id_log_detalhe';
    public $timestamps = false;

    // Funções de relacionamento
    public function log()
    {
        return $this->belongsTo(log::class, 'id_log');
    }

    // Funções especiais
    public function insere($args) {
        $this->id_log = $args['id_log'];
        $this->de_log_detalhe = $args['de_log_detalhe'];
        $this->dt_cadastro = Carbon::now();

        if ($this->save()) {
            return $this;
        } else {
            return;
        }
    }
}
