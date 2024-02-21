<?php

namespace App\Domain\Integracao\Models;

use Illuminate\Database\Eloquent\Model;

class integracao_registro_fiduciario extends Model
{
    protected $table = 'integracao_registro_fiduciario';
    protected $primaryKey = 'id_integracao_registro_fiduciario';
    public $timestamps = false;

    // Funções de relacionamento
	public function integracao()
    {
        return $this->belongsTo('App\Domain\Integracao\Models\integracao', 'id_integracao');
    }
	public function registro_fiduciario_tipo()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo', 'id_registro_fiduciario_tipo');
    }
	public function serventia()
    {
        return $this->belongsTo('App\Domain\Serventia\Models\serventia', 'id_serventia');
    }
	public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
}
