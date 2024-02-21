<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class monitor_atualizacao extends Model
{
    protected $table = 'monitor_atualizacao';
    protected $primaryKey = 'id_monitor_atualizacao';
    public $timestamps = false;
    protected $guarded  = [];
}
