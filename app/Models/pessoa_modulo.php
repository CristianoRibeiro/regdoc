<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class pessoa_modulo extends Model {

    protected $table = 'pessoa_modulo';
    protected $primaryKey = 'id_pessoa_modulo';
    public $timestamps = false;
    protected $guarded  = array();


    public function pessoa() {
        return $this->belongsTo(pessoa::class,'id_pessoa');
    }
    public function modulo() {
        return $this->belongsTo(modulo::class,'id_modulo');
    }


}
