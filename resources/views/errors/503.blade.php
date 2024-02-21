<!doctype html>
<html lang="en">
    <head>
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/libs/bootstrap.min.css')}}">
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

        <meta charset="utf-8">
        <title>Error 503</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            * {
                line-height: 1.2;
                margin: 0;
            }

            html {
                color: #888;
                display: table;
                font-family: sans-serif;
                height: 100%;
                text-align: center;
                width: 100%;

            }
            body {
                display: table-cell;
                vertical-align: middle;
                margin: 2em auto;
                background-color: #FBEFF2;
            }
            h1 {
                color: #FCFCFC;
                font-size: 200px;
                line-height: 1;
                margin-top: 20px;
                margin-bottom: 40px;
                font-weight: 300;
                display: block;
                text-shadow: 0 1px 0 #ccc,
                0 2px 0 #c9c9c9,
                0 3px 0 #bbb,
                0 4px 0 #b9b9b9,
                0 5px 0 #aaa,
                0 6px 1px rgba(0, 0, 0, 0.1),
                0 0 5px rgba(0, 0, 0, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.3),
                0 3px 5px rgba(0, 0, 0, 0.2),
                0 5px 10px rgba(0, 0, 0, 0.25),
                0 10px 10px rgba(0, 0, 0, 0.2),
                0 20px 20px rgba(0, 0, 0, 0.15);
            }

            p {
                margin: 0 auto;
                width: 280px;
            }

            @media only screen and (max-width: 280px) {
                body, p {
                    width: 95%;
                }
                h1 {
                    font-size: 1.5em;
                    margin: 0 0 0.3em;
                }
            }

            .btn-primary {
                color: #fff;
                background-color: #0339e5;
                border-color: #fff;
                border-radius: 0px;
            }

            .center-btn {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 350px;
            }

        </style>
    </head>
    <body>
        <h1 class="text-center">503</h1>
        <p class=" text-center">Oops, Serviço Indisponivel para manutenção (
            Service Unavailable for maintenance)
        </p>
        <br>
    </body>
</html>
