<?php

namespace App\Domain\Usuario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

use DB;
use Session;
use Carbon\Carbon;

class usuario extends Authenticatable
{
    use HasApiTokens;
    
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
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Funções de relacionamento
    public function pessoa()
    {
        return $this->belongsTo('App\Domain\Pessoa\Models\pessoa', 'id_pessoa');
    }
    public function usuario_senha()
    {
        return $this->hasMany('App\Domain\Usuario\Models\usuario_senha', 'id_usuario')->orderBy('dt_cadastro', 'DESC');
    }
    public function usuario_pessoa()
    {
        return $this->hasMany('App\Domain\Usuario\Models\usuario_pessoa', 'id_usuario');
    }
    public function pessoas()
    {
        return $this->belongsToMany('App\Domain\Pessoa\Models\pessoa', 'usuario_pessoa', 'id_usuario', 'id_pessoa');
    }
    public function unidade_gestora()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\unidade_gestora', 'id_unidade_gestora');
    }
    public function cargo()
    {
        return $this->hasOne('App\Domain\Usuario\Models\cargo', 'id_cargo');
    }
    public function perfil_usuario()
    {
        return $this->belongsTo('App\Domain\Usuario\Models\perfil_usuario', 'id_perfil_usuario');
    }
    public function pedido_usuario()
    {
        return $this->hasOne('App\Domain\Pedido\Models\pedido_usuario', 'id_usuario');
    }
    public function sessions()
    {
        return $this->hasMany('App\Models\sessions', 'user_id', 'id_usuario')->orderBy('last_activity', 'DESC');
    }
    public function registro_fiduciario_operadores()
    {
        return $this->hasMany('App\Domain\RegistroFiduciario\Models\registro_fiduciario_operador', 'id_usuario');
    }
    public function registro_fiduciario_operador_situacao()
    {
        $totais = $this->registro_fiduciario_operadores()
            ->select('situacao_pedido_grupo_produto.id_situacao_pedido_grupo_produto', DB::raw('count(pedido.id_pedido) as total_pedidos'))
            ->join('registro_fiduciario', 'registro_fiduciario.id_registro_fiduciario', '=', 'registro_fiduciario_operador.id_registro_fiduciario')
            ->join('registro_fiduciario_pedido', 'registro_fiduciario_pedido.id_registro_fiduciario', '=', 'registro_fiduciario.id_registro_fiduciario')
            ->join('pedido', 'pedido.id_pedido', '=', 'registro_fiduciario_pedido.id_pedido')
            ->join('situacao_pedido_grupo_produto', 'situacao_pedido_grupo_produto.id_situacao_pedido_grupo_produto', '=', 'pedido.id_situacao_pedido_grupo_produto')
            ->where('registro_fiduciario_operador.in_registro_ativo', 'S')
            ->whereNotIn('pedido.id_situacao_pedido_grupo_produto', [config('constants.SITUACAO.11.ID_CANCELADO')])
            ->groupBy('situacao_pedido_grupo_produto.id_situacao_pedido_grupo_produto')
            ->get()
            ->keyBy('id_situacao_pedido_grupo_produto')
            ->transform(function ($situacao_pedido_grupo_produto) {
                return $situacao_pedido_grupo_produto->total_pedidos;
            })
            ->toArray();

        $registros_operadores['nota_devolutiva'] = $totais[config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')] ?? 0;
        unset($totais[config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')]);
        $registros_operadores['finalizado'] = ($totais[config('constants.SITUACAO.11.ID_REGISTRADO')] ?? 0) + ($totais[config('constants.SITUACAO.11.ID_FINALIZADO')] ?? 0);
        unset($totais[config('constants.SITUACAO.11.ID_REGISTRADO')]);
        unset($totais[config('constants.SITUACAO.11.ID_FINALIZADO')]);
        $registros_operadores['em_andamento'] = array_sum($totais);

        return $registros_operadores;
    }

    // Atributos
    public function getDtUltimaAtividadeAttribute()
    {
        if (count($this->sessions)>0) {
            $last_activity = $this->sessions[0]->last_activity;

            return Carbon::parse($last_activity)->setTimezone(config('app.timezone'));
        }

        return false;
    }
    public function getInConectadoAttribute()
    {
        if ($this->dt_ultima_atividade) {
            return $this->dt_ultima_atividade > Carbon::now()->subMinutes(config('session.lifetime'));
        }

        return false;
    }

    // Atributos da sessão de pessoa ativa e passport
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
    public function findForPassport($identifier) {
        return $this->where('email_usuario', $identifier)
            ->orWhere('login', $identifier)
            ->first();
    }
}
