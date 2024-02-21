<?php

namespace App\Domain\Pessoa\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class pessoa extends Model
{
	protected $table = 'pessoa';
	protected $primaryKey = 'id_pessoa';
	public $timestamps = false;

	// Funções de relacionamento
    public function enderecos()
    {
        return $this->belongsToMany('App\Models\endereco', 'pessoa_endereco', 'id_pessoa', 'id_endereco')
					->where('pessoa_endereco.in_registro_ativo', '=', 'S')
					->orderBy('pessoa_endereco.id_pessoa_endereco', 'desc');
    }
    public function telefones()
    {
        return $this->belongsToMany('App\Models\telefone', 'pessoa_telefone', 'id_pessoa', 'id_telefone')
					->where('pessoa_telefone.in_registro_ativo', '=', 'S')
					->orderBy('pessoa_telefone.id_pessoa_telefone', 'desc');
    }
	public function tipo_pessoa()
	{
		return $this->belongsTo('App\Models\tipo_pessoa', 'id_tipo_pessoa');
	}
	public function usuario_pessoa()
	{
		return $this->hasMany('App\Models\usuario_pessoa', 'id_pessoa');
	}
	public function usuario()
	{
		return $this->hasOne('App\Models\usuario', 'id_pessoa');
	}
	public function serventia()
	{
		return $this->hasOne('App\Models\serventia', 'id_pessoa');
	}
    public function pessoa_endereco()
    {
        return $this->hasMany('App\Models\pessoa_endereco', 'id_pessoa')
            		->where('in_registro_ativo', '=', 'S')
            		->orderBy('id_pessoa_endereco', 'desc');
    }
    public function pessoa_telefone()
    {
        return $this->hasMany('App\Models\pessoa_telefone', 'id_pessoa')
            		->where('in_registro_ativo', '=', 'S')
            		->orderBy('id_pessoa_endereco', 'desc');
    }
	public function construtora()
	{
		return $this->hasMany('App\Domain\Construtora\Models\construtora', 'id_pessoa');
	}
	public function configuracao_pessoa()
    {
        return $this->hasMany('App\Domain\Configuracao\Models\configuracao_pessoa', 'id_pessoa');
    }
	public function logo_interna()
    {
		return $this->hasOne('App\Domain\Configuracao\Models\configuracao_pessoa', 'id_pessoa')->where('id_configuracao', 1);
    }
	public function pessoa_perfil_pessoa()
	{
		return $this->hasOne('App\Domain\Pessoa\Models\pessoa_perfil_pessoa', 'id_pessoa')
					->where('in_registro_ativo', 'S')
					->where('dt_ini_vigencia', '<=', Carbon::now())
					->where(function($where) {
						$where->where('dt_fim_vigencia', '>=', Carbon::now())
							  ->orWhereNull('dt_fim_vigencia');
					})
					->orderBy('dt_cadastro', 'DESC');
	}
	public function procuracoes_vinculadas()
	{
		return $this->belongsToMany('App\Domain\Procuracao\Models\procuracao', 'pessoa_procuracao', 'id_pessoa', 'id_procuracao');
	}
	public function credores_vinculados()
	{
		return $this->belongsToMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_credor', 'pessoa_registro_fiduciario_credor', 'id_pessoa', 'id_registro_fiduciario_credor');
	}
	public function registro_tipos_vinculados()
	{
		return $this->belongsToMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo', 'registro_fiduciario_tipo_pessoa', 'id_pessoa', 'id_registro_fiduciario_tipo');
	}
	public function construtoras_vinculadas()
	{
		return $this->hasMany('App\Domain\Construtora\Models\construtora', 'id_pessoa');
	}

	// Produtos
	public function pedidos()
	{
		return $this->hasMany('App\Domain\Pedido\Models\pedido', 'id_pessoa_origem', 'id_pessoa');
	}
}
