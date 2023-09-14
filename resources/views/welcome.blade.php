<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <style>
            h2{
                text-align: center;
                padding-top: 100px;
                font-size: 30px;
                //font-family: "David CLM";
            }
            .card{
                margin: 100px;
            }
            .card-body{
                background-color: lightblue;
                min-height: 500px;

            }

        </style>
        @vite(['resources/css/app.css', 'resources/js/bootstrap.js'])
    </head>
    <body class="antialiased">
        <h2>Struktura Drzewiasta Katalogów</h2>
        <div class="card" >
            <div class="card-body">
                <p>Drzewo Katalogów</p>
            </div>
        </div>

    </body>
</html>
