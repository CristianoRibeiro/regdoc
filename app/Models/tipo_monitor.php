<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipo_monitor extends Model
{
    protected $table = 'tipo_monitor';
    protected $primaryKey = 'id_tipo_monitor';
    public $timestamps = false;
    protected $guarded  = array();
}