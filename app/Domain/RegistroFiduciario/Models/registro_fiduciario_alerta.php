<?php

namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_alerta extends Model
{
    protected $table = 'registro_fiduciario_alerta';

    protected $primaryKey = 'id_registro_fiduciario_alerta';

    public $timestamps = false;

    // Funções de relacionamento
    public function registro_fiduciario_alerta_grupo()
    {
        return $this->belongsTo(registro_fiduciario_alerta_grupo::class,'id_registro_fiduciario_alerta_grupo');
    }
    public function registro_fiduciario_alerta_regra()
    {
        return $this->hasMany(registro_fiduciario_alerta_regra::class,'id_registro_fiduciario_alerta');
    }

    // Funções especiais
    public function total() {
        $total = $this->hasMany(registro_fiduciario_alerta_regra::class,'id_registro_fiduciario_alerta')
            ->join('registro_fiduciario_andamento_situacao', function($join) {
                $join->on('registro_fiduciario_andamento_situacao.id_acao_etapa', '=', 'registro_fiduciario_alerta_regra.id_acao_etapa')
                    ->where('registro_fiduciario_andamento_situacao.in_acao_salva', DB::raw('registro_fiduciario_alerta_regra.in_acao_salva'))
                    ->where('registro_fiduciario_andamento_situacao.in_registro_ativo', 'S');
            })
            ->join('registro_fiduciario_pedido','registro_fiduciario_pedido.id_registro_fiduciario_pedido', '=', 'registro_fiduciario_andamento_situacao.id_registro_fiduciario_pedido')
            ->join('pedido', function($join) {
                $join->on('pedido.id_pedido', '=', 'registro_fiduciario_pedido.id_pedido')
                    ->where('pedido.id_situacao_pedido_grupo_produto', DB::raw('registro_fiduciario_alerta_regra.id_situacao_pedido_grupo_produto'));
            });

        switch(Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 2:
                $total->join('pedido_pessoa',function($join) {
                    $join->on('pedido_pessoa.id_pedido', '=', 'pedido.id_pedido')
                        ->where('pedido_pessoa.id_pessoa', DB::raw(Auth::User()->pessoa_ativa->id_pessoa));
                });
                break;
            case 8:
                $total->where('pedido.id_pessoa_origem', DB::raw(Auth::User()->pessoa_ativa->id_pessoa));
                break;
        }

        return $total->count();
    }
}
