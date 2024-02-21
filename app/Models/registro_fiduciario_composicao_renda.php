<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_composicao_renda extends Model
{
    protected $table = 'registro_fiduciario_composicao_renda';
    protected $primaryKey = 'id_registro_fiduciario_composicao_renda';
    public $timestamps = false;

    // Funções especiais
    public function insere($args) 
    {
        $this->id_registro_fiduciario_operacao           = $args['id_registro_fiduciario_operacao'];
        $this->no_devedor                                = $args['no_devedor'];
        $this->va_renda_comprovada                       = $args['va_renda_comprovada'];
        $this->va_renda_nao_comprovada                   = $args['va_renda_nao_comprovada'];
        $this->va_percentual_renda_cobertura_securitaria = $args['va_percentual_renda_cobertura_securitaria'];
        $this->id_usuario_cad                            = Auth::User()->id_usuario;
        
        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }

    // Funções de relacionamento
    public function operacao()
    {
        return $this->belongsTo(registro_fiduciario_operacao::class,'id_registro_fiduciario_operacao');
    }
    // Funções de relacionamento
    public function usuario()
    {
        return $this->belongsTo(usuario::class,'id_usuario');
    }

}