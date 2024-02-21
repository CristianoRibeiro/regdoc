<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;


class compra_credito extends Model {

    protected $table = 'compra_credito';
    protected $primaryKey = 'id_compra_credito';
    public $timestamps = false;
    protected $guarded  = array();


    public function situcao_credito() {
        return $this->belongsTo('App\Models\situacao_credito','id_situacao_credito');
    }

    public function getVaCreditoAttribute($value)
    {
        return Helper::formatar_valor($value);
    }

    public function getDtCompraAttribute($value)
    {
        return Helper::formata_data($value);
    }

    public function usuario_credito()
    {
        return $this->belongsTo('App\Models\usuario', 'id_usuario');
    }

}