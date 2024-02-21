<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class serventia extends Model
{
	protected $table = 'serventia';
	protected $primaryKey = 'id_serventia';
    public $timestamps = false;

	// Funções de relacionamento
	public function serventia_cliente()
	{
		return $this->hasOne(serventia_cliente::class, 'id_serventia');
	}
	public function pessoa()
	{
		return $this->belongsTo(pessoa::class, 'id_pessoa');
	}
	public function unidade_gestora()
	{
		return $this->belongsTo(unidade_gestora::class, 'id_unidade_gestora');
	}
	public function tipo_serventia()
	{
		return $this->belongsTo(tipo_serventia::class, 'id_tipo_serventia');
	}

    /**
     * Retorna a Serventia do tipo "Notas" com menos registros fiduciários cadastrados
     *
     * @param $args
     * @return mixed
     */
    public function escolha_serventia_nota_automatica($args)
    {
        $serventias = $this;
        if ((isset($args['Estados']) && count($args['Estados'])>0) or ( isset($args['Cidades']) && count($args['Cidades'])>0))
        {
            $serventias = $serventias->join('pessoa', 'serventia.id_pessoa', '=', 'pessoa.id_pessoa')
                ->join('pessoa_endereco', 'pessoa_endereco.id_pessoa', '=', 'pessoa.id_pessoa')
                ->join('endereco', 'endereco.id_endereco', '=', 'pessoa_endereco.id_endereco')
                ->join('cidade', 'cidade.id_cidade', '=', 'endereco.id_cidade');

            if (isset($args['Estados']) && count($args['Estados'])>0)
            {
                $serventias = $serventias->whereIn('cidade.id_estado', $args['Estados']); #TODO: Trocar por código do IBGE
            }

            if ( isset($args['Cidades']) && count($args['Cidades'])>0)
            {
                $serventias = $serventias->whereIn('cidade.id_cidade', $args['Cidades']); #TODO: Trocar por código do IBGE
            }
        }

        if (isset($args['Bancos']) && count($args['Bancos'])>0)
        {
            $serventias = $serventias->join('serventia_cliente', 'serventia_cliente.id_serventia', '=', 'serventia.id_serventia')
                ->join('banco', function($join) use ($args)
                {
                    $join->on('banco.id_banco', '=', 'serventia_cliente.id_banco')
                        ->whereIn('banco.codigo_banco', $args['Bancos']);
                });
        }

        $id_serventia_max = $serventias
            ->select(DB::raw('serventia.id_serventia AS id_serventia_nota'))
            ->leftJoin('registro_fiduciario', 'registro_fiduciario.id_serventia_nota', '=', 'serventia.id_serventia')
            ->where('serventia.in_registro_ativo', 'S')
            ->where('serventia.id_tipo_serventia', 4)
            ->groupBy('serventia.id_serventia')
            ->orderBy(DB::raw('count(registro_fiduciario.id_serventia_nota)'), 'asc')
            ->first()->id_serventia_nota;

        return $this->find($id_serventia_max);

    }

    // Funcao que retorna as RIs de uma cidade especifica
    public static function getServentiasRINotasPorCidade($id_tipo_serventia, $id_cidade)
    {
        return DB::table('serventia')
                ->select(
                    'serventia'.'.id_serventia'
                    , 'serventia'.'.no_serventia'
                    , 'serventia'.'.id_pessoa'
                )
                ->join('pessoa', 'serventia.id_pessoa', '=', 'pessoa.id_pessoa')
                ->join('pessoa_endereco', 'pessoa.id_pessoa', '=', 'pessoa_endereco.id_pessoa')
                ->join('endereco', 'pessoa_endereco.id_endereco', '=', 'endereco.id_endereco')
                ->where('serventia'.'.id_tipo_serventia', $id_tipo_serventia)
                ->where('endereco.id_cidade', '=', $id_cidade)
                ->get();
    }
}
