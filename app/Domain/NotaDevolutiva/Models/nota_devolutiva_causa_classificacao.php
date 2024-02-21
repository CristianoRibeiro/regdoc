<?php

namespace App\Domain\NotaDevolutiva\Models;

use Illuminate\Database\Eloquent\Model;

class nota_devolutiva_causa_classificacao extends Model
{
	protected $table = 'nota_devolutiva_causa_classificacao';
	protected $primaryKey = 'id_nota_devolutiva_causa_classificacao';
	public $timestamps = false;

}
