<!doctype html>
<html lang="{{app()->getLocale()}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}" />
        @yield('meta')

        <title>REGDOC - @yield('titulo')</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha256-eSi1q2PG6J7g7ib17yAaWMcrr5GrtohYChqibrV7PBE=" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="{{asset('css/libs/bite-checkbox.css')}}">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        @yield('css')

        <script defer src="https://kit.fontawesome.com/9e48866546.js" crossorigin="anonymous"></script>
        <script defer src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha256-98vAGjEDGN79TjHkYWVD4s87rvWkdWLHPs5MC3FvFX4=" crossorigin="anonymous"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha256-VsEqElsCHSGmnmHXGQzvoWjWwoznFSZc6hs7ARLRacQ=" crossorigin="anonymous"></script>
        <script defer type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script defer type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/jquery.blockUI.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/jquery.countdown.min.js')}}"></script>
        <script defer type="text/javascript" src="{{asset('js/libs/multiselect.min.js')}}"></script>
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
            <div class="alert alert-danger mb-0">
                <div class="text-center">
                    <h3><strong>ATENÇÃO!</strong></h3>
                    Este ambiente é apenas para fins de <strong>testes</strong> e <strong>homologação</strong>. Quaisquer documentos emitidos neste ambiente não terão valor.
                </div>
            </div>
        @endif
		@yield('principal')
        @yield('loading', View::make('layouts.loading'))
    </body>
</html>
