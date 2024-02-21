<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class credito_entrada_saida extends Model {

    protected $table = 'credito_entrada_saida';
    protected $primaryKey = 'id_credito_entrada_saida';
    public $timestamps = false;
    protected $guarded  = array();

}