<?php

namespace App\Domain\NotaDevolutiva\Models;

use Illuminate\Database\Eloquent\Model;

class nota_devolutiva_cumprimento extends Model
{
	protected $table = 'nota_devolutiva_cumprimento';
	protected $primaryKey = 'id_nota_devolutiva_cumprimento';
	public $timestamps = false;

}
