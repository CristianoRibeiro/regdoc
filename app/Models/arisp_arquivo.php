<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;

class arisp_arquivo extends Model
{
    protected $table = 'arisp_arquivo';
    protected $primaryKey = 'id_arisp_arquivo';
    public $timestamps = false;
    protected $guarded  = array();

	// Funções de relacionamento
    public function arquivo_grupo_produto() {
        return $this->belongsTo(arquivo_grupo_produto::class, 'id_arquivo_grupo_produto');
    }

    public function insere(array $args) {
        $this->id_arquivo_grupo_produto = $args['id_arquivo_grupo_produto'];
        $this->codigo_arquivo = $args['codigo_arquivo'];
//        $this->dt_ultimo_download = $args['dt_ultimo_download'];
        $this->id_usuario_cad = Auth::User()->id_usuario;
        $this->dt_cadastro = Carbon::now();

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}
