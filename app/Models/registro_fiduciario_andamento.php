<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon\Carbon;

use App\Domain\Arisp\Models\arisp_boleto;

class registro_fiduciario_andamento extends Model
{
    protected $table        = 'registro_fiduciario_andamento';
    protected $primaryKey   = 'id_registro_fiduciario_andamento';
    public $timestamps      = false;

    // Funções de relacionamento
    public function fase_grupo_produto()
    {
        return $this->belongsTo(fase_grupo_produto::class,'id_fase_grupo_produto');
    }

    public function etapa_fase()
    {
        return $this->belongsTo(etapa_fase::class,'id_etapa_fase');
    }
    public function acao_etapa()
    {
        return $this->belongsTo(acao_etapa::class,'id_acao_etapa');
    }

    public function resultado_acao()
    {
        return $this->belongsTo(resultado_acao::class,'id_resultado_acao');
    }

    public function arquivos_grupo()
    {
        return $this->belongsToMany(arquivo_grupo_produto::class,'registro_fiduciario_andamento_arquivo_grupo','id_registro_fiduciario_andamento','id_arquivo_grupo_produto');
    }

    public function arisp_boletos()
    {
        return $this->belongsToMany(arisp_boleto::class,'registro_fiduciario_andamento_arisp_boleto','id_registro_fiduciario_andamento','id_arisp_boleto');
    }

    public function usuario_cad()
    {
        return $this->belongsTo(usuario::class,'id_usuario_cad','id_usuario');
    }

    public function usuario_acao()
    {
        return $this->belongsTo(usuario::class,'id_usuario_acao','id_usuario');
    }

    public function usuario_resultado()
    {
        return $this->belongsTo(usuario::class,'id_usuario_resultado','id_usuario');
    }

    public function pessoa_acao()
    {
        return $this->belongsTo(pessoa::class,'id_pessoa_acao','id_pessoa');
    }

    public function pessoa_resultado()
    {
        return $this->belongsTo(pessoa::class,'id_pessoa_resultado','id_pessoa');
    }
    public function registro_fiduciario_pedido()
    {
        return $this->belongsTo(registro_fiduciario_pedido::class,'id_registro_fiduciario_pedido');
    }


    // Funções especiais
    public function insere($args)
    {
        $this->id_fase_grupo_produto = $args['id_fase_grupo_produto'];
        $this->id_etapa_fase = $args['id_etapa_fase'];
        $this->id_acao_etapa = $args['id_acao_etapa'];
        $this->id_resultado_acao = (isset($args['id_resultado_acao'])?$args['id_resultado_acao']:NULL);
        $this->id_usuario_etapa = Auth::User()->id_usuario;
        $this->id_usuario_acao = Auth::User()->id_usuario;
        $this->id_usuario_resultado = (isset($args['id_resultado_acao'])?Auth::User()->id_usuario:NULL);
        $this->id_usuario_cad = Auth::User()->id_usuario;
        $this->id_registro_fiduciario_pedido = $args['id_registro_fiduciario_pedido'];
        $this->in_acao_salva = $args['in_acao_salva'];
        $this->in_resultado_salvo = $args['in_resultado_salvo'];

        if ($args['in_acao_salva']=='S')
        {
            $this->dt_acao_cad = Carbon::now();
            $this->id_pessoa_acao = Auth::User()->pessoa_ativa->id_pessoa;
        }
        if ($args['in_resultado_salvo']=='S')
        {
            $this->dt_resultado_cad = Carbon::now();
            $this->id_pessoa_resultado = Auth::User()->pessoa_ativa->id_pessoa;
        }

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }

    public function insere_proximo_andamento($resultado_acao)
    {
        if ($resultado_acao) {
            $novo_registro_fiduciario_andamento = new registro_fiduciario_andamento();
            $novo_registro_fiduciario_andamento->id_fase_grupo_produto = $resultado_acao->id_nova_fase_grupo_produto;
            $novo_registro_fiduciario_andamento->id_etapa_fase = $resultado_acao->id_nova_etapa_fase;
            $novo_registro_fiduciario_andamento->id_acao_etapa = $resultado_acao->id_nova_acao_etapa;
            $novo_registro_fiduciario_andamento->id_usuario_etapa = Auth::User()->id_usuario;
            $novo_registro_fiduciario_andamento->id_usuario_cad = Auth::User()->id_usuario;
            $novo_registro_fiduciario_andamento->id_registro_fiduciario_pedido = $this->id_registro_fiduciario_pedido;

            if ($resultado_acao->nova_acao_etapa->in_resultado_direto=='S') {
                $novo_registro_fiduciario_andamento->in_acao_salva = "S";
                $novo_registro_fiduciario_andamento->dt_acao_cad = Carbon::now();
                $novo_registro_fiduciario_andamento->id_pessoa_acao = Auth::User()->pessoa_ativa->id_pessoa;
            }
            return $novo_registro_fiduciario_andamento->save();
        } else {
            return false;
        }
    }

    public function atualiza($args)
    {
        if ($this->id_registro_fiduciario_andamento && !empty($args)) {
            if (isset($args['de_texto_curto_acao'])) {
                $this->de_texto_curto_acao = $args['de_texto_curto_acao'];
            }
            if (isset($args['de_texto_longo_acao'])) {
                $this->de_texto_longo_acao = $args['de_texto_longo_acao'];
            }
            if (isset($args['dt_acao'])) {
                $this->dt_acao = $args['dt_acao'];
            }
            if (isset($args['va_valor_acao'])) {
                $this->va_valor_acao = $args['va_valor_acao'];
            }
            if (isset($args['in_acao_salva'])) {
                $this->in_acao_salva = $args['in_acao_salva'];
                $this->id_usuario_acao = ($args['in_acao_salva']=='S'?Auth::User()->id_usuario:NULL);

                if ($args['in_acao_salva']=='S')
                {
                    $this->dt_acao_cad = Carbon::now();
                    $this->id_pessoa_acao = Auth::User()->pessoa_ativa->id_pessoa;
                }
            }

            $this->de_texto_curto_resultado = (isset($args['de_texto_curto_resultado'])?$args['de_texto_curto_resultado']:NULL);
            $this->de_texto_longo_resultado = (isset($args['de_texto_longo_resultado'])?$args['de_texto_longo_resultado']:NULL);
            $this->dt_resultado = (isset($args['dt_resultado'])?$args['dt_resultado']:NULL);
            $this->va_valor_resultado = (isset($args['va_valor_resultado'])?$args['va_valor_resultado']:NULL);

            if (isset($args['in_resultado_salvo'])) {
                $this->in_resultado_salvo = $args['in_resultado_salvo'];
                $this->id_usuario_resultado = ($args['in_resultado_salvo'] == 'S'?Auth::User()->id_usuario:NULL);

                if ($args['in_resultado_salvo']=='S')
                {
                    $this->dt_resultado_cad = Carbon::now();
                    $this->id_pessoa_resultado = Auth::User()->pessoa_ativa->id_pessoa;
                }
            }

            $this->id_resultado_acao = (isset($args['id_resultado_acao'])?$args['id_resultado_acao']:NULL);
            $this->dt_acao_cad = (isset($args['id_resultado_acao'])?Carbon::now():NULL);

            return $this->save();
        }
        return false;
    }
}
