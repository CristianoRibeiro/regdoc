<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>@yield('titulo')</title>

        <style>
            @page {
                margin: 2.5cm 1.25cm !important;
                padding: 0px 0px 0px 0px !important;
            }
            body {
                font-family: sans-serif;
                font-size: 10pt;
                line-height: 1.5;
                padding: 0 !important;
            }
            header {
                color: #999;
                position: fixed;
                top: -70px;
                left: 0px;
                right: 0px;
                height: 50px;
            }
            h1, h2, h3, h4, h5 {
                margin-bottom: 10px;
            }
            br {
                font-size: 65%;
            }

            .page-break {
                page-break-after: always;
            }
            .uppercase {
                text-transform: uppercase;
            }
            .small {
                font-size: 85%;
            }
            .center {
                display: block;
                text-align: center;
            }
            .justify {
                text-align: justify;
            }
            .right {
                text-align: right;
            }

            .ident-1 {
                text-indent: 0.7cm;
            }
            .ident-2 {
                text-indent: 1.4cm;
            }
            .ident-3 {
                text-indent: 2.1cm;
            }

            table.border {
                border-collapse: collapse !important;
            }
            table.border td {
                border: 1px solid #000 !important;
            }
            table.padding td {
                padding: 3px 5px 5px 5px !important;
            }

            .m-0 {
                margin: 0 !important;
            }
            .mt-10 {
                margin-top: 10px !important;
            }
            .ml-20 {
                margin-left: 20px !important;
            }
            .ml-40 {
                margin-left: 40px !important;
            }

            @yield('css-pdf')
        </style>
    </head>
    <body>
        @yield('conteudo')
    </body>
</html>
