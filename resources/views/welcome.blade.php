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
                background-color: white;
                min-height: 500px;
                border: 1px solid black;

            }
            div.element {
                width: 100%;

                margin-left: 20px;
                margin-top: 10px;

            }
            .row{
                min-height: 500px;
            }
            div.element > div.element{
                width: 90%;

            }
            .btn-dark{
                text-align: center;
            }


        </style>
        @vite(['resources/css/app.css', 'resources/js/bootstrap.js'])
    </head>

    <body class="antialiased">
        <h2>Struktura Drzewiasta Katalogów</h2>
        <div class="card" >
            <div class="card-body rounded">

                <!--<button  class="btn btn-dark" id="przycisk-rozwin-zwin" onclick="toggleWszystkiePodkatalogi()">Rozwiń/Zwiń Wszystko</button>-->
                <div class="row">
                    <div class="col">
                        <div id="drzewo-katalogow"></div>
                    </div>

                    <div class="col" style="border-left: 1px solid black;">
                        <div class="row">
                            <div class="col text-center">

                                <button type="submit" class="btn btn-primary" id="formularz-dodaj">Dodaj</button>

                            </div>
                            <div class="col text-center">

                                <a href="" class="btn btn-secondary">
                                    Edytuj
                                </a>

                            </div>
                            <div class="col text-center">

                                <button type="submit" class="btn btn-danger" id="formularz-usun">Usuń</button>

                            </div>
                            <div class="container" id="formularz" style="display:none; padding: 50px">
                                <form action="{{route('katalog.dodaj')}}" method="POST">
                                    @csrf
                                    <label>Nazwa Katalogu</label>
                                    <input type="text" name="nazwa" class="form-control" required/>

                                    <label>Sciezka (Opcjonalnie) <small><i>Np. /Ten Komputer/Folder/</i></small></label>
                                    <input type="text" name="sciezka" class="form-control"/>
                                    <button type="submit" class="btn btn-dark" >Dodaj</button>
                                </form>
                            </div>
                            <div class="container" id="formularzUsuniecia" style="display:none; padding: 50px">
                                <form action="{{route('katalog.usun')}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <label>Wprowadź nazwę katalogu lub sciezke</label>
                                    <input type="text" name="nazwa" class="form-control" required/>

                                    <button type="submit" class="btn btn-dark" >Usuń</button>
                                </form>
                            </div>
                        </div>



                    </div>
                  </div>



            </div>
        </div>
        @if ($errors->has('nazwa'))
            <div class="container alert alert-danger">
                {{ $errors->first('nazwa') }}
            </div>
        @endif

    </body>



</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById("formularz-dodaj").addEventListener("click", function () {
        var formularz = document.getElementById("formularz");
        formularz.style.display = "block"; // Pokaż formularz
    });
    document.getElementById("formularz-usun").addEventListener("click", function () {
        var formularz = document.getElementById("formularzUsuniecia");
        formularz.style.display = "block"; // Pokaż formularz
    });
    document.addEventListener("DOMContentLoaded", function() {





    fetch('/pobierz-katalogi')
        .then(response => response.json())
        .then(data => {
            data = JSON.parse(data);

        //katalogi ktore nie maja rodzica (Katalogi Główne)
        function renderKatalogi(katalogi, rodzic_id = null) {
            const divContainer = document.createElement('div');
            katalogi.forEach(katalog => {
                if (katalog.rodzic_id === rodzic_id) {

                    const divElement = document.createElement('div');
                    divElement.classList.add('element');

                    // Ikona folderu
                    const folderIcon = document.createElement('span');
                    folderIcon.innerHTML = '&#128193;';

                    // Nazwa katalogu
                    const nazwaKatalogu = document.createElement('span');
                    nazwaKatalogu.textContent = katalog.nazwa;
                    nazwaKatalogu.classList.add('nazwa-katalogu');

                    // Obsługa klikniecia na katalog w celu rozwijania/zwijania
                    nazwaKatalogu.addEventListener('click', () => {

                        const podkatalog = divElement.querySelector('ul');
                        if (podkatalog) {

                            if (podkatalog.style.display === 'none' || podkatalog.style.display === '') {

                                podkatalog.style.display = 'block';

                            } else {

                                podkatalog.style.display = 'none';

                            }
                        }
                    });

                    divElement.appendChild(folderIcon);
                    divElement.appendChild(nazwaKatalogu);
                    divElement.classList.add('kontener');

                    //render podkatalogów
                    const podKatalog = renderKatalogi(katalogi, katalog.id);
                    if (podKatalog) {
                        // Zawiera podkatalogi
                        const ulPodkatalog = document.createElement('ul');
                        ulPodkatalog.appendChild(podKatalog);
                        ulPodkatalog.style.display = 'none'; //defaultowo ukryj podkatalogi
                        divElement.appendChild(ulPodkatalog);
                    }

                    divContainer.appendChild(divElement);
                }
            });

            return divContainer.children.length ? divContainer : null;
        }




        const drzewoUI = renderKatalogi(data);


        if (drzewoUI) {
            document.getElementById('drzewo-katalogow').appendChild(drzewoUI);
        }

    }).catch(error=>console.error(error));





});


</script>
