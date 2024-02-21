<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_checklist extends Model
{
    protected $table = 'registro_fiduciario_checklist';
    protected $primaryKey = 'id_registro_fiduciario_checklist';
    public $timestamps = false;

    public function registro_fiduciario() {
        return $this->belongsTo(registro_fiduciario::class,'id_registro_fiduciario');
    }
    public function checklist() {
        return $this->belongsTo('App\Domain\Checklist\Models\checklist','id_checklist');
    }
}
