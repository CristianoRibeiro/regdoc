<?php
namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_apresentante extends Model
{
    protected $table = 'registro_fiduciario_apresentante';
    protected $primaryKey = 'id_registro_fiduciario_apresentante';
    public $timestamps = false;

    public function cidade()
    {
        return $this->belongsTo('App\Domain\Estado\Models\cidade', 'id_cidade');
    }
}
