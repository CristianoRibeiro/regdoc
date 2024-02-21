<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\Arquivo\Models\arquivo_grupo_produto;

class arisp_pedido extends Model
{
    protected $table = 'arisp_pedido';
    protected $primaryKey = 'id_arisp_pedido';
    public $timestamps = false;
    protected $guarded  = array();

	// Funções de relacionamento
    public function pedido()
    {
        return $this->belongsTo(pedido::class, 'id_pedido');
    }
    public function arisp_pedido_status()
    {
        return $this->belongsTo(arisp_pedido_status::class, 'id_arisp_pedido_status');
    }
    public function arisp_pedido_historico()
    {
        return $this->hasMany(arisp_pedido_historico::class, 'id_arisp_pedido')->orderBy('dt_cadastro', 'DESC');
    }
    public function arquivos()
    {
        return $this->belongsToMany(arquivo_grupo_produto::class, 'arisp_pedido_arquivo_grupo', 'id_arisp_pedido', 'id_arquivo_grupo_produto');
    }
}
