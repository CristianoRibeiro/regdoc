<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class situacao_credito extends Model{

    protected $table = 'situacao_credito';
    protected $primaryKey = 'id_situacao_credito';
    public $timestamps = false;
    protected $guarded  = array();


    public function situacao_credito() {
        return $this->hasMany('App\compra_credito','id_situacao_credito');
    }

}