<?php

namespace App\Models;

use http\Env\Response;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon\Carbon;
use DB;
use Storage;

use App\Models\historico_pedido;

class pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;

    // Funções de relacionamento
    public function situacao_pedido_grupo_produto()
    {
        return $this->belongsTo(situacao_pedido_grupo_produto::class, 'id_situacao_pedido_grupo_produto');
    }

    public function pessoas()
    {
        return $this->belongsToMany(pessoa::class, 'pedido_pessoa', 'id_pedido', 'id_pessoa');
    }

    public function pedido_pessoa()
    {
        return $this->hasOne(pedido_pessoa::class, 'id_pedido')->orderBy('dt_cadastro', 'asc');
    }

    public function pedido_pessoa_atual()
    {
        return $this->hasOne(pedido_pessoa::class, 'id_pedido')->orderBy('dt_cadastro', 'desc');
    }

    public function pessoa_origem()
    {
        return $this->belongsTo(pessoa::class, 'id_pessoa_origem', 'id_pessoa');
    }
    public function usuario_cad()
    {
        return $this->belongsTo(usuario::class, 'id_usuario_cad', 'id_usuario');
    }

    public function pedido_usuario()
    {
        return $this->hasMany(pedido_usuario::class, 'id_pedido')->orderBy('dt_cadastro', 'asc');
    }

    public function produto_item()
    {
        return $this->belongsToMany(produto_item::class, 'pedido_produto_item', 'id_pedido', 'id_produto_item');
    }
    public function produto()
    {
        return $this->belongsTo(produto::class, 'id_produto', 'id_produto');
    }

    // Relações com Registro Fiduciário
    public function registro_fiduciario_pedido()
    {
        return $this->hasOne(registro_fiduciario_pedido::class, 'id_pedido');
    }

    public function registro_fiduciario()
    {
        return $this->belongsToMany(registro_fiduciario::class, 'registro_fiduciario_pedido'
            , 'id_pedido', 'id_registro_fiduciario');
    }

    public function arisp_pedido()
    {
        return $this->hasOne(arisp_pedido::class, 'id_pedido')->orderBy('dt_cadastro', 'DESC');
    }

    // Funções especiais
    public function insere($args)
    {
        $this->id_usuario = Auth::User()->id_usuario;
        $this->id_situacao_pedido_grupo_produto = $args['id_situacao_pedido_grupo_produto'];
        $this->id_produto = $args['id_produto'];
        $this->id_alcada = $args['id_alcada'];
        $this->protocolo_pedido = $args['protocolo_pedido'];
        $this->dt_pedido = Carbon::now();
        $this->nu_quantidade = $args['nu_quantidade'];
        $this->de_pedido = $args['de_pedido'];
        $this->va_pedido = $args['va_pedido'];
        $this->id_usuario_cad = Auth::User()->id_usuario;
        $this->id_pessoa_origem = $args['id_pessoa_origem'];

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }

    /**
     *
     * @return mixed Retora o obj cidade vinculado a serventia deste pedido
     */
    public function getCidadeServentia()
    {
        if ($this->registro_fiduciario()) {
            $serventia = $this->registro_fiduciario()->first()->serventia()->first();

            return $serventia->pessoa()->first()->enderecos()->first()->cidade()->first();
        }

        return $serventia->pessoa()->first()->enderecos()->first()->cidade()->first();

    }

    public function registrar_valor_pedido($dados = array())
    {
        $id_pedido = $this->id_pedido;
        $id_usuario = Auth::User()->id_usuario;
        $id_usuario_logado = Auth::User()->id_usuario;
        $va_pedido = ($dados['va_pedido'] == null) ? $this->va_pedido : $dados['va_pedido'];
        $tp_movimentacao_financeira = $dados['tp_movimentacao_financeira'];
        $tp_movimentacao = $dados['tp_movimentacao'];
        $id_forma_pagamento = ($dados['id_forma_pagamento'] == null) ? 'null' : $dados['id_forma_pagamento'];
        $v_id_pedido_desconto = ($dados['v_id_pedido_desconto'] == null) ? 'null' : $dados['v_id_pedido_desconto'];
        $v_va_desconto = $dados['v_va_desconto'];
        $v_in_desconto = $dados['v_in_desconto'];
        $v_va_desconto_perc = ($dados['v_va_desconto_perc'] == null) ? 'null' : $dados['v_va_desconto_perc'];
        $v_id_compra_credito = ($dados['v_id_compra_credito'] == null) ? 'null' : $dados['v_id_compra_credito'];

        return DB::select(DB::raw("select cerafi.f_registrar_valor_pedido (
                                                                $id_pedido,
                                                                $id_usuario,
                                                                $id_usuario_logado,
                                                                $va_pedido,
                                                                $tp_movimentacao_financeira,
                                                                '$tp_movimentacao',
                                                                $id_forma_pagamento,
                                                                $v_id_pedido_desconto,
                                                                $v_va_desconto,
                                                                '$v_in_desconto',
                                                                $v_va_desconto_perc,
                                                                $v_id_compra_credito
                                                              );"));


    }

}
