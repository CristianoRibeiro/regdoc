<?php
namespace App\Domain\TabelaEmolumento\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\TabelaEmolumento\Models\tabela_emolumento;

class tabela_emolumento_faixa extends Model
{
    protected $table = 'tabela_emolumento_faixa';
    protected $primaryKey = 'id_tabela_emolumento_faixa';
    public $timestamps = false;

    // Funções de relacionamento
    public function tabela_emolumento()
    {
        return $this->belongsTo(tabela_emolumento::class,'id_tabela_emolumento');
    }
}
