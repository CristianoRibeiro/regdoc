<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class forma_pagamento extends Model {

    protected $table = 'forma_pagamento';
    protected $primaryKey = 'id_forma_pagamento';
    public $timestamps = false;
    protected $guarded  = array();

}