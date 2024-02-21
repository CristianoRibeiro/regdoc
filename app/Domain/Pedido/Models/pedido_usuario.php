<?php

namespace App\Domain\Pedido\Models;

use Illuminate\Database\Eloquent\Model;

class pedido_usuario extends Model
{
    protected $table = 'pedido_usuario';
    protected $primaryKey = 'id_pedido_usuario';
    public $timestamps = false;

    // Funções de relacionamento
    public function pedido()
    {
        return $this->belongsTo('App\Domain\Pedido\Models\pedido','id_pedido');
    }
    public function usuario()
    {
		return $this->belongsTo('App\Domain\Usuario\Models\usuario','id_usuario');
	}
    public function pedido_usuario_senha()
    {
        return $this->hasOne('App\Domain\Pedido\Models\pedido_usuario_senha','id_pedido_usuario')->where('in_registro_ativo','S');
    }
    public function registro_fiduciario_parte()
    {
        return $this->hasOne('App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte','id_pedido_usuario');
    }
    public function registro_fiduciario_procurador()
    {
        return $this->hasOne('App\Domain\RegistroFiduciario\Models\registro_fiduciario_procurador','id_pedido_usuario');
    }
    public function documento_parte()
    {
        return $this->hasOne('App\Domain\Documento\Parte\Models\documento_parte','id_pedido_usuario');
    }
    public function documento_procurador()
    {
        return $this->hasOne('App\Domain\Documento\Parte\Models\documento_procurador','id_pedido_usuario');
    }
}
