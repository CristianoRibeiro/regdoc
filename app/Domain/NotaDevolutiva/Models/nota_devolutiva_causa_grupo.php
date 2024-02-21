<?php

namespace App\Domain\NotaDevolutiva\Models;

use Illuminate\Database\Eloquent\Model;

class nota_devolutiva_causa_grupo extends Model
{
	protected $table = 'nota_devolutiva_causa_grupo';
	protected $primaryKey = 'id_nota_devolutiva_causa_grupo';
	public $timestamps = false;

	public function nota_devolutiva_causa_classificacao()
    {
        return $this->belongsTo('App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_classificacao', 'id_nota_devolutiva_causa_classificacao');
    }
}
