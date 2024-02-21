<?php
namespace App\Domain\TabelaEmolumento\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domain\TabelaEmolumento\Models\tabela_emolumento_tipo;
use App\Domain\TabelaEmolumento\Models\tabela_emolumento_faixa;
use App\Models\tipo_serventia;
use App\Domain\Estado\Models\estado;
use App\Domain\Estado\Models\cidade;

class tabela_emolumento extends Model
{
    protected $table = 'tabela_emolumento';
    protected $primaryKey = 'id_tabela_emolumento';
    public $timestamps = false;

    // Funções de relacionamento
    public function tabela_emolumento_tipo()
    {
        return $this->belongsTo(tabela_emolumento_tipo::class, 'id_tabela_emolumento_tipo');
    }
    public function tabela_emolumento_faixa()
    {
        return $this->hasMany(tabela_emolumento_faixa::class, 'id_tabela_emolumento');
    }
    public function tipo_serventia()
    {
        return $this->belongsTo(tipo_serventia::class, 'id_tipo_serventia');
    }
    public function estado()
    {
        return $this->belongsTo(estado::class, 'id_estado');
    }
    public function cidade()
    {
        return $this->belongsTo(cidade::class, 'id_cidade');
    }
}
