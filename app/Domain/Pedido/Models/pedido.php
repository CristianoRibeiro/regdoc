<?php

namespace App\Domain\Pedido\Models;

use Illuminate\Database\Eloquent\Model;

class pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;

    // Funções de relacionamento
    public function situacao_pedido_grupo_produto()
    {
        return $this->belongsTo('App\Models\situacao_pedido_grupo_produto', 'id_situacao_pedido_grupo_produto');
    }
    public function pessoas()
    {
        return $this->belongsToMany('App\Domain\Pessoa\Models\pessoa', 'pedido_pessoa', 'id_pedido', 'id_pessoa');
    }
    public function pedido_pessoa()
    {
        return $this->hasOne('App\Domain\Pedido\Models\pedido_pessoa', 'id_pedido')->orderBy('dt_cadastro', 'asc');
    }
    public function pedido_pessoa_atual()
    {
        return $this->hasOne('App\Domain\Pedido\Models\pedido_pessoa', 'id_pedido')->orderBy('dt_cadastro', 'desc');
    }
    public function pessoa_origem()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa_origem', 'id_pessoa');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
    public function pedido_usuario()
    {
        return $this->hasMany('App\Domain\Pedido\Models\pedido_usuario', 'id_pedido')->orderBy('dt_cadastro', 'asc');
    }
    public function produto_item()
    {
        return $this->belongsToMany('App\Models\produto_item', 'pedido_produto_item', 'id_pedido', 'id_produto_item');
    }
    public function produto()
    {
        return $this->belongsTo('App\Models\produto', 'id_produto', 'id_produto');
    }
    public function registro_fiduciario_pedido()
    {
        return $this->hasOne('App\Domain\RegistroFiduciario\Models\registro_fiduciario_pedido', 'id_pedido');
    }
    public function registro_fiduciario()
    {
        return $this->belongsToMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario', 'registro_fiduciario_pedido', 'id_pedido', 'id_registro_fiduciario');
    }
    public function documento()
    {
        return $this->hasOne('App\Domain\Documento\Documento\Models\documento', 'id_pedido');
    }
    public function arisp_pedido()
    {
        return $this->hasMany('App\Models\arisp_pedido', 'id_pedido')->orderBy('dt_cadastro', 'DESC');
    }
    public function historico_pedido()
    {
        return $this->hasMany('App\Domain\Pedido\Models\historico_pedido','id_pedido')->orderBy('dt_cadastro','desc')->orderBy('id_historico_pedido', 'DESC');
    }
    public function pedido_central()
    {
        return $this->hasMany('App\Domain\Pedido\Models\pedido_central','id_pedido')->orderBy('dt_cadastro', 'DESC');
    }
}
