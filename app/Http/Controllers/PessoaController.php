<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\pessoa;

class PessoaController extends Controller
{
    public function lista(Request $request)
    {
        $pessoas = [];

        if (count($request->id_tipo_pessoa)>0) {
            $pessoas = new pessoa();

            if ($request->id_cidade) {
                $pessoas = $pessoas->join('pessoa_endereco', 'pessoa_endereco.id_pessoa', '=', 'pessoa.id_pessoa')
                                   ->join('endereco', function($join) use ($request) {
                                        $join->on('endereco.id_endereco', '=', 'pessoa_endereco.id_endereco')
                                             ->where('endereco.id_cidade', $request->id_cidade);
                                   });
            }

            if (count($request->id_tipo_serventia) > 0) {
                $pessoas = $pessoas->join('serventia', 'serventia.id_pessoa', '=', 'pessoa.id_pessoa');
                $pessoas = $pessoas->whereIn('serventia.id_tipo_serventia', $request->id_tipo_serventia);
            }

            $pessoas = $pessoas->where('pessoa.in_registro_ativo', 'S')
                               ->whereIn('id_tipo_pessoa', $request->id_tipo_pessoa)
                               ->orderBy('pessoa.no_pessoa', 'asc')
                               ->get();
        }
        return response()->json($pessoas);
    }
}
