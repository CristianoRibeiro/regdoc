<?php
namespace App\Models;

use App\Model\banco_pessoa;
use Illuminate\Database\Eloquent\Model;

use Exception;
use Illuminate\Notifications\Notifiable;

class pessoa extends Model
{
	protected $table = 'pessoa';
	protected $primaryKey = 'id_pessoa';
    public $timestamps = false;

    use Notifiable;

	// Funções de relacionamento
    public function enderecos()
    {
        return $this->belongsToMany(endereco::class,'pessoa_endereco','id_pessoa','id_endereco')->where('pessoa_endereco.in_registro_ativo', '=','S')->orderBy('pessoa_endereco.id_pessoa_endereco', 'desc');
    }
    public function telefones()
    {
        return $this->belongsToMany(telefone::class,'pessoa_telefone','id_pessoa','id_telefone')->where('pessoa_telefone.in_registro_ativo', '=','S')->orderBy('pessoa_telefone.id_pessoa_telefone', 'desc');
    }
	public function nacionalidade()
	{
		return $this->belongsTo(nacionalidade::class,'id_nacionalidade');
	}
	public function estado_civil()
	{
		return $this->belongsTo(estado_civil::class,'id_estado_civil');
	}
	public function tipo_pessoa()
	{
		return $this->belongsTo(tipo_pessoa::class,'id_tipo_pessoa');
	}
	public function usuario_pessoa() {
		return $this->hasMany(usuario_pessoa::class,'id_pessoa');
	}
	public function pessoa_modulo() {
		return $this->hasMany(pessoa_modulo::class,'id_pessoa');
	}
	public function usuario()
	{
		return $this->hasOne(usuario::class,'id_pessoa');
	}
	public function serventia() {
		return $this->hasOne(serventia::class,'id_pessoa');
	}

    public function pessoa_endereco()
    {
        return $this->hasMany(pessoa_endereco::class,'id_pessoa')
            ->where('in_registro_ativo', '=','S')
            ->orderBy('id_pessoa_endereco', 'desc');
    }
    public function pessoa_telefone()
    {
        return $this->hasMany(pessoa_telefone::class,'id_pessoa')
            ->where('in_registro_ativo', '=','S')
            ->orderBy('id_pessoa_endereco', 'desc');
    }

	// Funções especiais
    public function insere($args)
    {
        $this->no_pessoa = $args['no_pessoa'];
        $this->tp_pessoa = $args['tp_pessoa'];
        $this->nu_cpf_cnpj = $args['nu_cpf_cnpj'];
        $this->nu_rg = (isset($args['nu_rg'])?$args['nu_rg']:NULL);
        $this->no_orgao_emissor_rg = (isset($args['no_orgao_emissor_rg'])?$args['no_orgao_emissor_rg']:NULL);
        $this->dt_emissao_rg = (isset($args['dt_emissao_rg'])?$args['dt_emissao_rg']:NULL);
        $this->nu_cnh = (isset($args['nu_cnh'])?$args['nu_cnh']:NULL);
        $this->nu_passaporte = (isset($args['nu_passaporte'])?$args['nu_passaporte']:NULL);
        $this->nu_outro_documento = (isset($args['nu_outro_documento'])?$args['nu_outro_documento']:NULL);
        $this->nu_inscricao_estadual = (isset($args['nu_inscricao_estadual'])?$args['nu_inscricao_estadual']:NULL);
        $this->nu_inscricao_municipal = (isset($args['nu_inscricao_municipal'])?$args['nu_inscricao_municipal']:NULL);
        $this->no_fantasia = (isset($args['no_fantasia'])?$args['no_fantasia']:NULL);
        $this->tp_sexo = (isset($args['tp_sexo'])?$args['tp_sexo']:'N');
        $this->no_email_pessoa = (isset($args['no_email_pessoa'])?$args['no_email_pessoa']:NULL);
        $this->dt_nascimento = (isset($args['dt_nascimento'])?$args['dt_nascimento']:NULL);
        $this->id_cidade_nascimento = (isset($args['id_cidade_nascimento'])?$args['id_cidade_nascimento']:NULL);
        $this->id_estado_civil = (isset($args['id_estado_civil'])?$args['id_estado_civil']:NULL);
        $this->id_nacionalidade = (isset($args['id_nacionalidade'])?$args['id_nacionalidade']:NULL);
        $this->id_pais = (isset($args['id_pais'])?$args['id_pais']:NULL);
        $this->co_uf_nascimento = (isset($args['co_uf_nascimento'])?$args['co_uf_nascimento']:NULL);
        $this->in_registro_ativo = (isset($args['in_registro_ativo'])?$args['in_registro_ativo']:'S');
        $this->id_tipo_pessoa = $args['id_tipo_pessoa'];

        if ($this->save()) {
        	if (isset($args['pessoa_telefone'])) {
				$novo_telefone = new telefone();

				if ($novo_telefone->insere($args['pessoa_telefone'])) {
					$this->telefones()->attach($novo_telefone);
				} else {
					throw new Exception('Erro ao salvar a o telefone da pessoa.');
				}
        	}
        	return $this;
        } else {
        	false;
        }
    }
}
