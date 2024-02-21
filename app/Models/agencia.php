<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class agencia extends Model
{
    protected $table = 'agencia';
    protected $primaryKey = 'id_agencia';
    public $timestamps = false;

    public function cidade() {
        return $this->belongsTo(cidade::class,'id_cidade');
    }

    public function banco()
    {
        return $this->belongsTo(banco::class, 'id_banco');
    }
}
