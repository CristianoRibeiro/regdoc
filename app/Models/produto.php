<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class produto extends Model
{
	protected $table = 'produto';
	protected $primaryKey = 'id_produto';
    public $timestamps = false;

	// Funções de relacionamento
	public function produto_itens() {
		return $this->hasMany('App\produto_item','id_produto');
	}
	public function grupo_produto()
	{
		return $this->belongsTo(grupo_produto::class, 'id_grupo_produto', 'id_grupo_produto');
	}
}
