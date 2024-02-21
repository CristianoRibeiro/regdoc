<?php
namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_pedido extends Model
{
    protected $table = 'registro_fiduciario_pedido';
    protected $primaryKey = 'id_registro_fiduciario_pedido';
    public $timestamps = false;

    // Funções de relacionamento
    public function pedido()
    {
        return $this->belongsTo('App\Domain\Pedido\Models\pedido', 'id_pedido');
    }
    public function registro_fiduciario()
    {
        return $this->belongsTo('App\Domain\RegistroFiduciario\Models\registro_fiduciario', 'id_registro_fiduciario');
    }
    public function registro_fiduciario_andamento()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento', 'id_registro_fiduciario_pedido')->where('in_acao_salva','S')->orderBy('id_registro_fiduciario_andamento','desc');
    }
    public function registro_fiduciario_andamento_atual()
    {
        return $this->hasOne('App\Domain\RegistroFiduciario\Models\registro_fiduciario_andamento', 'id_registro_fiduciario_pedido')->orderBy('id_registro_fiduciario_andamento','desc');
    }
}
