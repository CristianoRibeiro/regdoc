<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Carbon\Carbon;
use Exception;
use Auth;
use Session;
use Laravel\Passport\HasApiTokens;

class usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    // Sobrescrita das funções padrões do Laravel
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
    public function getReminderEmail()
    {
        return $this->email_usuario;
    }
    public function getAuthPassword()
    {
        if (count($this->usuario_senha)>0) {
            return $this->usuario_senha()->where('dt_ini_periodo', '<=', Carbon::now())
                                         ->where(function($where) {
                                            $where->where('dt_fim_periodo', '>=', Carbon::now())
                                                  ->orWhereNull('dt_fim_periodo');
                                         })
                                         ->orderBy('dt_cadastro','desc')
                                         ->first()
                                         ->senha;
        } else {
            return false;
        }
    }
    public function getRememberToken()
    {
        return $this->lembrar_token;
    }
    public function setRememberToken($value)
    {
        $this->lembrar_token = $value;
    }
    public function getRememberTokenName()
    {
        return 'lembrar_token';
    }

    /* Atributos da sessão de pessoa ativa */
    public function getPessoaAtivaAttribute()
    {
        return Session::get('pessoa_ativa', NULL);
    }
    public function setPessoaAtivaAttribute($value)
    {
        Session::put('pessoa_ativa', $value);
    }
    public function getPessoaAtivaInUsuarioMasterAttribute()
    {
        return Session::get('pessoa_ativa_in_usuario_master', NULL);
    }
    public function setPessoaAtivaInUsuarioMasterAttribute($value)
    {
        Session::put('pessoa_ativa_in_usuario_master', $value);
    }
    public function getPedidoAtivoAttribute()
    {
        return Session::get('pedido_ativo', NULL);
    }
    public function setPedidoAtivoAttribute($value)
    {
        Session::put('pedido_ativo', $value);
    }

    // Funções de relacionamento
    public function pessoa()
    {
        return $this->belongsTo(pessoa::class,'id_pessoa');
    }
    public function usuario_senha()
    {
        return $this->hasMany(usuario_senha::class,'id_usuario');
    }
    public function usuario_pessoa()
    {
        return $this->hasMany(usuario_pessoa::class,'id_usuario');
    }
    public function unidade_gestora()
    {
        return $this->belongsTo(unidade_gestora::class,'id_unidade_gestora');
    }

    public function cargo()
    {
        return $this->hasOne(cargo::class,'id_cargo');
    }

    public function perfil_usuario()
    {
        return $this->belongsTo(perfil_usuario::class, 'id_perfil_usuario');
    }

    // Funções especiais
    public function insere($args)
    {
        if (isset($args['pessoa'])) {
            $nova_pessoa = new pessoa();
            if ($nova_pessoa->insere($args['pessoa'])) {
                $id_pessoa = $nova_pessoa->id_pessoa;
            } else {
                throw new Exception('Erro ao salvar a nova pessoa.');
            }
        } elseif (isset($args['id_pessoa'])) {
            $pessoa = new pessoa();
            $pessoa = $pessoa->find($args['id_pessoa']);
            if ($pessoa->id_pessoa>0) {
                $id_pessoa = $pessoa->id_pessoa;
            } else {
                throw new Exception('A pessoa informada não foi encontrada.');
            }
        } else {
            throw new Exception('A pessoa não foi informada.');
        }

        $this->id_pessoa = $id_pessoa;
        $this->no_usuario = $args['no_usuario'];
        $this->email_usuario = $args['email_usuario'];
        $this->login = (isset($args['login'])?$args['login']:NULL);
        if (isset($args['in_registro_ativo'])) {
            $this->dt_usuario_ativo = ($args['in_registro_ativo']=='S'?Carbon::now():NULL);
            $this->in_registro_ativo = $args['in_registro_ativo'];
        }
        $this->dt_ini_periodo = (isset($args['dt_ini_periodo'])?$args['dt_ini_periodo']:Carbon::now());
        $this->dt_fim_periodo = (isset($args['dt_fim_periodo'])?$args['dt_fim_periodo']:NULL);
        $this->in_alterar_senha = (isset($args['in_alterar_senha'])?$args['in_alterar_senha']:'N');
        $this->id_usuario_cad = Auth::User()->id_usuario;
        $this->id_unidade_gestora = 1;
        $this->confirmar_token = (isset($args['confirmar_token'])?$args['confirmar_token']:NULL);
        $this->campo_flex_01 = (isset($args['campo_flex_01'])?$args['campo_flex_01']:NULL);
        if (isset($args['in_confirmado'])) {
            $this->dt_usuario_confirmado = ($args['in_confirmado']=='S'?Carbon::now():NULL);
            $this->in_confirmado = $args['in_confirmado'];
        }
        if (isset($args['in_aprovado'])) {
            $this->dt_usuario_aprovado = ($args['in_aprovado']=='S'?Carbon::now():NULL);
            $this->in_aprovado = $args['in_aprovado'];
        }
        $this->id_cargo = (isset($args['id_cargo'])?$args['id_cargo']:NULL);
        $this->in_cliente = (isset($args['in_cliente'])?$args['in_cliente']:'N');

        if ($this->save()) {
            $args_usuario_pessoa = [
                'id_usuario' => $this->id_usuario,
                'id_pessoa' => $id_pessoa
            ];
            $novo_usuario_pessoa = new usuario_pessoa();
            if (!$novo_usuario_pessoa->insere($args_usuario_pessoa)) {
                throw new Exception('Erro ao salvar a relação entre pessoa e usuário.');
            }
            return $this;
        } else {
            return false;
        }
    }

}
