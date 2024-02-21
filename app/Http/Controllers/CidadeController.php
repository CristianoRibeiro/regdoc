<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\cidade;
use App\Models\estado;

class CidadeController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lista(Request $request)
    {
        $cidades = null;

        if ($request->id_estado > 0) {
            $cidades = new cidade();
            $cidades = $cidades->where('id_estado', $request->id_estado)
                               ->orderBy('no_cidade', 'asc')
                               ->get();
            return response()->json($cidades);
        } else if (!empty($request->uf_estado)) {
            $cidades = new cidade();
            $cidades = $cidades->where('uf', $request->uf_estado)
                ->orderBy('no_cidade', 'asc')
                ->get();
            return response()->json($cidades);
        }
    }

    public function lista_estado()
    {
        $estados = new estado();
        $estados = $estados->orderBy('no_estado')->get();
        return $estados;
    }
}
