<!doctype html>
<html lang="{{app()->getLocale()}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}" />

        <title>REGDOC - @yield('titulo')</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" type="text/css" href="{{asset('css/protocolo.css')}}?v={{config('app.version')}}">
        @yield('css')

        <script defer src="https://kit.fontawesome.com/9e48866546.js" crossorigin="anonymous"></script>
        <script defer type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script defer type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script defer type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
        <script defer type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script defer type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/autoNumeric.min.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/bootstrap-datepicker.min.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/i18n/bootstrap-datepicker.pt-BR.min.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/bootstrap-select.min.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/i18n/bootstrap-select.pt_BR.min.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/jquery.mask.min.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/sweetalert2.all.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/jquery.blockUI.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/jquery.base64.min.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/polichat.js')}}?v={{config('app.version')}}"></script>
        <script type="application/javascript">
            var URL_BASE = '{{URL::to("/")}}/';
            var URL_ATUAL = '{{request()->url()}}/';
            var APP_DEBUG = {{config('app.debug')?'true':'false'}};
        </script>
        @yield('js')
    </head>
    <body>
        @if(config('app.env')!='production')
            <div class="alert alert-light-danger mb-0">
                <div class="text-center">
                    <h3><strong>ATENÇÃO!</strong></h3>
                    Este ambiente é apenas para fins de <strong>testes</strong> e <strong>homologação</strong>. Quaisquer documentos emitidos neste ambiente não terão valor.
                </div>
            </div>
        @endif
		@yield('conteudo')
        <div class="container text-center mb-3 text-secondary">
            &copy; {{Carbon\Carbon::now()->format('Y')}} REGDOC | <strong>Valid Hub</strong> uma empresa do grupo Valid Soluções S.A.
        </div>

        @yield('loading', View::make('layouts.loading'))

        @yield('end-js')
    </body>
</html>
