<div>{{$katalog->nazwa}}</div>
@if ($katalog->podkatalogi->isNotEmpty())
    <div class="nested-catalogs">
        @foreach($katalog->podkatalogi as $childCatalog)
            @include('katalog', ['katalog' => $childCatalog])
        @endforeach
    </div>
@endif
