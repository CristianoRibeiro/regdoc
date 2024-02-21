<?php

namespace App\Domain\ProdutoItem\Models;

use App\Models\produto;

use Illuminate\Database\Eloquent\Model;

use DB;
use Helper;

class produto_item extends Model
{
    protected $table = 'produto_item';

    protected $primaryKey = 'id_produto_item';

    public $timestamps = false;

    // Funções de relacionamento
    public function produto() {
        return $this->belongsTo(produto::class,'id_produto');
    }

    // Funções especiais
    public function lista_preco($ids_pessoa="") {
        $valores = DB::select(DB::raw("SELECT * FROM ".config('database.connections.pgsql.schema').".f_lista_preco_produto_pessoa ('".$this->id_produto_item."', '".$ids_pessoa."', ';', 'L') as (descricao text, va_preco text);"));
        return $valores;
    }
    public function preco_total($ids_pessoa="") {
        $valores = $this->lista_preco($ids_pessoa);
        return Helper::converte_float(end($valores)->va_preco);
    }
}