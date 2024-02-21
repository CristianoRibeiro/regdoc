<?php
namespace App\Domain\RegistroFiduciario\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon\Carbon;
use Session;

class registro_fiduciario_andamento extends Model
{
    protected $table = 'registro_fiduciario_andamento';
    protected $primaryKey = 'id_registro_fiduciario_andamento';
    public $timestamps = false;

    // Funções de relacionamento
    public function fase_grupo_produto()
    {
        return $this->belongsTo('App\Models\fase_grupo_produto', 'id_fase_grupo_produto');
    }
    public function etapa_fase()
    {
        return $this->belongsTo('App\Models\etapa_fase', 'id_etapa_fase');
    }
    public function acao_etapa()
    {
        return $this->belongsTo('App\Models\acao_etapa', 'id_acao_etapa');
    }
    public function resultado_acao()
    {
        return $this->belongsTo('App\Models\resultado_acao', 'id_resultado_acao');
    }
    public function arquivos_grupo()
    {
        return $this->belongsToMany('App\Domain\Arquivo\Models\arquivo_grupo_produto', 'registro_fiduciario_andamento_arquivo_grupo', 'id_registro_fiduciario_andamento', 'id_arquivo_grupo_produto');
    }
    public function usuario_cad()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_cad', 'id_usuario');
    }
    public function usuario_acao()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_acao', 'id_usuario');
    }
    public function usuario_resultado()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\usuario', 'id_usuario_resultado', 'id_usuario');
    }
    public function pessoa_acao()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa_acao', 'id_pessoa');
    }
    public function pessoa_resultado()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa_resultado', 'id_pessoa');
    }
}
