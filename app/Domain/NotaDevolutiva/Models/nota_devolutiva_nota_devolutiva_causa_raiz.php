<?php

namespace App\Domain\NotaDevolutiva\Models;

use Illuminate\Database\Eloquent\Model;

class nota_devolutiva_nota_devolutiva_causa_raiz extends Model
{
	protected $table = 'nota_devolutiva_nota_devolutiva_causa_raiz';
	protected $primaryKey = 'id_nota_devolutiva_nota_devolutiva_causa_raiz';
	public $timestamps = false;

	public function nota_devolutiva_causa_grupo()
    {
        return $this->belongsTo('App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_grupo', 'id_nota_devolutiva_causa_grupo');
    }
	public function nota_devolutiva_causa_raiz()
    {
        return $this->belongsTo('App\Domain\NotaDevolutiva\Models\nota_devolutiva_causa_raiz', 'id_nota_devolutiva_causa_raiz');
    }
	public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
}
