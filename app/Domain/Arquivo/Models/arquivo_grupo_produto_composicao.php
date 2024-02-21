<?php
namespace App\Domain\Arquivo\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class arquivo_grupo_produto_composicao extends Model
{
	protected $table = 'arquivo_grupo_produto_composicao';
	protected $primaryKey = 'id_arquivo_grupo_produto_composicao';
    public $timestamps = false;

}
