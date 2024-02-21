<?php

namespace App\Http\Controllers;

use App\Models\monitor;
use Illuminate\Http\Request;
use Auth;

class AcompanhamentoStatusController extends Controller
{
	public function index(Request $request)
	{
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 1)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Cadastro de contrato'
        ];

		return view('app.acompanhamento.status', $compact_args);
	}

    public function emissao(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 2)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Emissão de certificado'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

    public function assinatura(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 3)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Assinatura'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

    public function itbi(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 4)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'ITBI'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

    public function guiaprenotacao(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 5)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Guia Prenotação'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

    public function guiaemolumento(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 6)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Guia Emolumento'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

    public function prenotacao(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 7)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Prenotação'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

    public function averbacao(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 8)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Averbação'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

    public function emolumento(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 9)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Emolumentos'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

    public function cartorio(Request $request)
    {
        $monitor = new monitor();
        $monitor = $monitor->where('id_tipo_monitor', 10)->orderBy('nu_ordem', 'ASC')->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'monitor' => $monitor ?? [],
            'title' => 'Entrada em cartório'
        ];

        return view('app.acompanhamento.status', $compact_args);
    }

}
