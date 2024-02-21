<?php
namespace App\Domain\TabelaEmolumento\Models;

use Illuminate\Database\Eloquent\Model;

class tabela_emolumento_tipo extends Model
{
    protected $table = 'tabela_emolumento_tipo';
    protected $primaryKey = 'id_tabela_emolumento_tipo';
    public $timestamps = false;

}
