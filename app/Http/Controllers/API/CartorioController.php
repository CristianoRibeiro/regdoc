<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\API\IndexCartorios;

use App\Domain\Pessoa\Models\pessoa;
use App\Domain\Estado\Models\cidade;

class CartorioController extends Controller
{
    public function index(IndexCartorios $request)
    {
        $cidade = new cidade();
        $cidade = $cidade->where("co_ibge", $request->cidade)->first();

        $pessoas = new pessoa();
        $pessoas = $pessoas->where('id_tipo_pessoa', 2)
            ->where('pessoa.in_registro_ativo', 'S')
            ->join('pessoa_endereco', 'pessoa_endereco.id_pessoa', '=', 'pessoa.id_pessoa')
            ->join('endereco', function($join) use ($cidade) {
                $join->on('endereco.id_endereco', '=', 'pessoa_endereco.id_endereco')
                    ->where('endereco.id_cidade', $cidade->id_cidade);
            });

        if ($request->tipo_cartorio>0) {
            $pessoas = $pessoas->join('serventia', 'serventia.id_pessoa', '=', 'pessoa.id_pessoa')
                ->where('serventia.id_tipo_serventia', $request->tipo_cartorio);
        }

        $pessoas = $pessoas->orderBy('pessoa.no_pessoa', 'asc')
            ->get();

        $serventias = [];
        foreach($pessoas as $pessoa) {
            $serventias[] = [
                'cns' => $pessoa->serventia->codigo_cns_completo,
                'nome' => $pessoa->no_pessoa,
                'tipo' => $pessoa->serventia->id_tipo_serventia,
                'cidade' => [
                    'nome' => $pessoa->enderecos[0]->cidade->no_cidade,
                    'co_ibge'=> $pessoa->enderecos[0]->cidade->co_ibge,
                    'uf' => $pessoa->enderecos[0]->cidade->estado->uf,
                ]
            ];
        }

        $response_json = [
            'cartorios' => $serventias
        ];
        return response()->json($response_json, 200);
    }
}
