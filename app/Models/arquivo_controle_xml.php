<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Auth;

class arquivo_controle_xml extends Model
{
    protected $table = 'arquivo_controle_xml';
    protected $primaryKey = 'id_arquivo_controle_xml';
    public $timestamps = false;

    // Funções de relacionamento
    public function arquivo_controle_xml_situacao()
    {
        return $this->belongsTo(arquivo_controle_xml_situacao::class,'id_arquivo_controle_xml_situacao');
    }
    public function usuario_certificado()
    {
        return $this->belongsTo(usuario_certificado::class,'id_usuario_certificado');
    }
    public function registro_fiduciario_arquivo_xml()
    {
        return $this->hasMany(registro_fiduciario_arquivo_xml::class,'id_arquivo_controle_xml');
    }

    // Funções especiais
    public function insere($args)
    {
        $this->id_arquivo_controle_xml_situacao = $args['id_arquivo_controle_xml_situacao'];
        $this->id_arquivo_controle_xml_tipo = $args['id_arquivo_controle_xml_tipo'];
        $this->no_arquivo = $args['no_arquivo'];
        $this->dt_arquivo = Carbon::now();
        $this->no_diretorio_arquivo = $args['no_diretorio_arquivo'];
        $this->nu_registro_processados = 0;
        $this->protocolo = $args['protocolo'];
        $this->in_assinatura_digital = $args['in_assinatura_digital'];
        $this->id_usuario_certificado = $args['id_usuario_certificado'];
        $this->dt_processado = Carbon::now();
        $this->id_usuario_processado = Auth::User()->id_usuario;
        $this->id_usuario_cad = Auth::User()->id_usuario;

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}
