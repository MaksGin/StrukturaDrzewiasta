<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <style>

            h2{
                text-align: center;
                padding-top: 20px;
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

                margin-left: 30px;
                margin-top: 10px;

            }
            .row{
                min-height: 500px;
            }
            div.element > div.element{
                width: 100%;

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


                <div class="row">
                    <div class="col">

                        <button class="btn btn-dark" id="Rozwin" onclick="rozwinFoldery()">Rozwiń wszystkie katalogi</button>

                        <div id="drzewo-katalogow"></div>
                    </div>

                    <div class="col" style="border-left: 1px solid black;">

                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- obsługa wyświetlania errorow -->
                            @if ($errors->any())
                                <div class="container alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        <div class="container" style="padding: 50px">
                                <h2>Stwórz nowy katalog</h2>
                                <form action="{{route('katalog.dodaj')}}" method="POST">
                                    @csrf
                                    <label>Nazwa Katalogu</label>
                                    <input type="text" name="nazwa" class="form-control" required/>

                                    <label>Sciezka (Opcjonalnie) <small><i>Np. /Ten Komputer/Folder/</i></small></label>
                                    <input type="text" name="sciezka" class="form-control"/>

                                    <center><button type="submit" class="btn btn-dark">Dodaj</button></center>
                                </form>

                            </div>

                            <h2>Edytuj istniejący katalog</h2>
                            <div class="container" style="padding: 50px">
                                <form action="{{route('katalog.edytuj')}}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label>Podaj ścieżke do katalogu: <small><i>Dla katalogu głównego: (/Ten Komputer)</i></small></label>
                                    <input type="text" name="sciezka" class="form-control" required/>
                                    <label>Wprowadź nowa nazwę: </label>
                                    <input type="text" name="nowaNazwa" class="form-control" required/>
                                    <center><button type="submit" class="btn btn-dark" >Zatwierdź</button></center>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>



    </body>



</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let stanRozwiniecia = false; // Zmienna do śledzenia stanu drzewa katalogów

    function rozwinFoldery() {
        //pobieram wszystkie elementy
        const elementy = document.querySelectorAll('#drzewo-katalogow ul');

        //przechodzę przez elementy i zmieniam stan
        elementy.forEach(ul => {
            if (stanRozwiniecia) {
                ul.style.display = 'none';
            } else {
                ul.style.display = 'block';
            }
        });

        // zmienam stan przycisku
        stanRozwiniecia = !stanRozwiniecia;

    }





    fetch('/pobierz-katalogi')
        .then(response => response.json())
        .then(data => {
            data = JSON.parse(data);


            //katalogi ktore nie maja rodzica (Katalogi Główne)
            function renderKatalogi(katalogi, rodzic_id = null, sortuj = false) {
                const divContainer = document.createElement('div');
                if (sortuj) {
                    katalogi.sort((a, b) => a.nazwa.localeCompare(b.nazwa));
                }
                katalogi.forEach(katalog => {
                    if (katalog.rodzic_id === rodzic_id) {

                        const divElement = document.createElement('div');
                        divElement.classList.add('glowne');
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

                        nazwaKatalogu.addEventListener('dblclick', () => {

                            //trzeba przekazać token podczas operacji usuwania bez tego miałem bład csrf token missmatch ;)
                            var csrfToken = $('meta[name="csrf-token"]').attr('content');

                            const folderId = katalog.id; // Przyjmuję, że 'id' to unikalne ID folderu

                            if (confirm('Czy na pewno chcesz usunąć ten folder?')) {
                                $.ajax({
                                    url: '/usun-folder',
                                    method: 'DELETE',
                                    data: JSON.stringify({id: folderId}),
                                    contentType: 'application/json',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    }

                                })
                                    .done(function (data) {
                                        console.log(data);
                                        folderIcon.remove();
                                        nazwaKatalogu.remove();
                                        podKatalog.remove();
                                    })
                                    .fail(function (error) {
                                        console.error('Błąd aktualizacji statusu zamówienia');
                                    });
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

                }).catch(error => console.error(error))

        document.addEventListener("DOMContentLoaded", function() {



        });
</script>
