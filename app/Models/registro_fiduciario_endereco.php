<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_endereco extends Model
{
    protected $table = 'registro_fiduciario_endereco';
    protected $primaryKey = 'id_registro_fiduciario_endereco';
    public $timestamps = false;

    // Relações com Cidade
    public function cidade()
    {
        return $this->belongsTo(cidade::class,'id_cidade');
    }
    public function registro_fiduciario_imovel_localizacao()
    {
        return $this->belongsTo(registro_fiduciario_imovel_localizacao::class, 'id_registro_fiduciario_imovel_localizacao');
    }

    // Funções especiais
    public function insere($args)
    {
        $this->id_registro_fiduciario = $args['id_registro_fiduciario'];
        $this->id_cidade = (isset($args['id_cidade'])?$args['id_cidade']:NULL);
        $this->no_endereco = $args['no_endereco'];
        $this->no_complemento = (isset($args['no_complemento'])?$args['no_complemento']:NULL);
        $this->nu_numero = (isset($args['nu_numero'])?$args['nu_numero']:NULL);
        $this->no_bairro = (isset($args['no_bairro'])?$args['no_bairro']:NULL);
        $this->nu_cep = (isset($args['nu_cep'])?$args['nu_cep']:NULL);
        $this->in_devedor = (isset($args['in_devedor'])?$args['in_devedor']:NULL);
        $this->in_bem = (isset($args['in_bem'])?$args['in_bem']:NULL);
        $this->in_reutilizar = (isset($args['in_reutilizar'])?$args['in_reutilizar']:NULL);
		$this->id_usuario_cad = Auth::User()->id_usuario;

        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
    public function atualiza($args)
    {
        $registro_fiduciario_endereco = registro_fiduciario_endereco::find($args['id_registro_fiduciario_endereco']);

        $registro_fiduciario_endereco->id_registro_fiduciario = $args['id_registro_fiduciario'];
        $registro_fiduciario_endereco->id_cidade = (isset($args['id_cidade'])?$args['id_cidade']:NULL);
        $registro_fiduciario_endereco->no_endereco = $args['no_endereco'];
        $registro_fiduciario_endereco->no_complemento = (isset($args['no_complemento'])?$args['no_complemento']:NULL);
        $registro_fiduciario_endereco->nu_numero = (isset($args['nu_numero'])?$args['nu_numero']:NULL);
        $registro_fiduciario_endereco->no_bairro = (isset($args['no_bairro'])?$args['no_bairro']:NULL);
        $registro_fiduciario_endereco->nu_cep = (isset($args['nu_cep'])?$args['nu_cep']:NULL);
        $registro_fiduciario_endereco->in_devedor = (isset($args['in_devedor'])?$args['in_devedor']:NULL);
        $registro_fiduciario_endereco->in_bem = (isset($args['in_bem'])?$args['in_bem']:NULL);
        $registro_fiduciario_endereco->in_reutilizar = (isset($args['in_reutilizar'])?$args['in_reutilizar']:NULL);
        $registro_fiduciario_endereco->id_usuario_cad = Auth::User()->id_usuario;

        if ($registro_fiduciario_endereco->update()){
            return $this;
        } else {
            return false;
        }
    }
}
