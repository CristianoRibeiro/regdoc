<?php

namespace App\Domain\Checklist\Models;

use Illuminate\Database\Eloquent\Model;

class checklist extends Model
{
	protected $table = 'checklist';
	protected $primaryKey = 'id_checklist';
	public $timestamps = false;

	// Funções de relacionamento
}
