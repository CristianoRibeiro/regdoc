<?php

namespace App\Domain\Checklist\Models;

use Illuminate\Database\Eloquent\Model;

class checklist_registro_fiduciario extends Model
{
	protected $table = 'checklist_registro_fiduciario';
	protected $primaryKey = 'id_checklist_registro_fiduciario';
	public $timestamps = false;

	// Funções de relacionamento
	public function checklist()
    {
        return $this->belongsTo('App\Domain\Checklist\Models\checklist', 'id_checklist');
    }
	public function registro_fiduciario_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo', 'id_registro_fiduciario_tipo');
    }
	public function integracao()
    {
        return $this->belongsTo('App\Domain\Integracao\Models\integracao', 'id_integracao');
    }
	public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
}
