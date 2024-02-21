<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class situacao_pedido_grupo_produto extends Model
{
    protected $table = 'situacao_pedido_grupo_produto';
    protected $primaryKey = 'id_situacao_pedido_grupo_produto';
    public $timestamps = false;

    public function pedidos()
    {
        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 8:
                return $this->hasMany('App\Domain\Pedido\Models\pedido', 'id_situacao_pedido_grupo_produto')
                            ->join('pedido_pessoa', function ($join) {
                                $join->on('pedido_pessoa.id_pedido', '=', 'pedido.id_pedido')
                                     ->where('pedido_pessoa.id_pessoa', Auth::User()->pessoa_ativa->id_pessoa);
                            });
                break;
            default:
                return $this->hasMany('App\Domain\Pedido\Models\pedido', 'id_situacao_pedido_grupo_produto');
                break;
        }
    }
}
