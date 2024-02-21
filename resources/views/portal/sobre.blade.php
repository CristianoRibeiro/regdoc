@extends('portal.layouts.principal')

@section('portal')
    <section id="conteudos">
        <div class="container">
            <h3 class="titulo text-center text-md-left">Sobre o REGDOC</h3>
            <div class="row mt-3">
                <div class="texto col-12 col-md-9 text-justify">
                    <p>O REGDOC – Soluções de Registro Eletrônico é uma solução desenvolvida pela Valid Hub uma empresa inovadora que provém soluções tecnológicas integradoras entre os sistemas cartoriais, financeiros e órgãos públicos que disponibiliza de forma inovadora as conexões dos todos interessados na formalização dos registros de contratos do Brasil de forma ampla e organizada e completamente eletrônica necessária em todos os passos para aquisição de crédito para bens imóveis (casas e apartamentos) e bens móveis (veículos automotores).</p>
                    <p>Todos os processos do REGDOC utilizam as mais inovadoras soluções da Valid Soluções S.A. Sua plataforma tecnológica integra imobiliárias, instituições financeiras, cartórios extrajudiciais, incorporadoras, etc.</p>
                    <p>A formalização dos nossos contratos é toda realizada de forma eletrônica completamente sem uso de papel. Através do uso do certificado digital concedido as partes dos registros o que garante agilidade e dá segurança jurídica as partes.</p>
                </div>
                <div class="logo col d-none d-md-block">
                    <img src="{{asset('img/logo-03.png')}}" class="img-fluid" />
                </div>
            </div>
        </div>
    </section>
@endsection
