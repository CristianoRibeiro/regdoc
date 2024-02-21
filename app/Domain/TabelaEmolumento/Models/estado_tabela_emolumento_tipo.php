<?php
namespace App\Domain\TabelaEmolumento\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\produto;
use App\Domain\Estado\Models\estado;
use App\Domain\TabelaEmolumento\Models\tabela_emolumento_tipo;

class estado_tabela_emolumento_tipo extends Model
{
    protected $table = 'estado_tabela_emolumento_tipo';
    protected $primaryKey = 'id_estado_tabela_emolumento_tipo';
    public $timestamps = false;

    // Funções de relacionamento
    public function estado()
    {
        return $this->belongsTo(estado::class, 'id_estado');
    }
    public function produto()
    {
        return $this->belongsTo(produto::class, 'id_produto');
    }
    public function tabela_emolumento_tipo()
    {
        return $this->belongsTo(tabela_emolumento_tipo::class, 'id_tabela_emolumento_tipo');
    }
}
