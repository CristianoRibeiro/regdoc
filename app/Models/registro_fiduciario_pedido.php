<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registro_fiduciario_pedido extends Model
{
    protected $table = 'registro_fiduciario_pedido';
    protected $primaryKey = 'id_registro_fiduciario_pedido';
    public $timestamps = false;

    // Funções de relacionamento
    public function pedido()
    {
        return $this->belongsTo(pedido::class,'id_pedido');
    }
    public function registro_fiduciario()
    {
        return $this->belongsTo(registro_fiduciario::class,'id_registro_fiduciario');
    }
    public function registro_fiduciario_andamento()
    {
        return $this->hasMany(registro_fiduciario_andamento::class,'id_registro_fiduciario_pedido')->orderBy('id_registro_fiduciario_andamento','desc');
    }
	public function registro_fiduciario_andamento_atual()
	{
		return $this->hasOne(registro_fiduciario_andamento::class,'id_registro_fiduciario_pedido')->orderBy('id_registro_fiduciario_andamento','desc');
	}

    public function arquivos_andamentos() {
        return $this->hasManyThrough(registro_fiduciario_andamento_arquivo_grupo::class, registro_fiduciario_andamento::class, 'id_registro_fiduciario_pedido', 'id_registro_fiduciario_andamento');
    }
}
