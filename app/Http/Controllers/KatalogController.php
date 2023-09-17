<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Katalog;
use Illuminate\Validation\Rule;
class KatalogController extends Controller
{


    public function index(){
        $katalogi = Katalog::all();

        $rodzic_id = Null;
        return view('welcome',compact('katalogi','rodzic_id'));
    }

    public function getKatalogi(){
        $katalogi = Katalog::orderBy('nazwa','asc')->get();

        $DataJson = $katalogi->toJson();

        return response()->json($DataJson);

    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {

        $validatedData = $request->validate([
            'nazwa' => [
                'required',
                'regex:/^[a-zA-Z0-9\s\-_]+$/u', //wyrazenie regularne
                Rule::unique('katalogi', 'nazwa')
                    ->where(function ($query) use ($request) {
                        // Dodaj warunek, aby unikalna nazwa była w ramach tego samego rodzica
                        $query->where('rodzic_id', $request->input('rodzic_id'));
                    })

            ],
        ], [
            'nazwa.regex' => 'Nazwa katalogu może zawierać tylko litery, cyfry, spacje, myślniki i podkreślenia.',
            'nazwa.unique' => 'Taki Katalog już istnieje w innym miejscu',
        ]);


        $sciezka = $request->input('sciezka');

        //dziele sciezke na segmenty
        $segmenty = explode('/', trim($sciezka,'/'));

        $katalogNadrzedny = null;

        //iteruje przez segmenty szukajac rodzicow :)
        foreach ($segmenty as $segment) {

            $katalogNadrzedny = Katalog::where('nazwa', $segment)
                ->where('rodzic_id', $katalogNadrzedny ? $katalogNadrzedny->id : null)
                ->first();

        }


        $katalog = new Katalog();
        $katalog->nazwa = $validatedData['nazwa'];

        // Ustawianie katalogu nadrzędnego
        $katalog->rodzic_id = $katalogNadrzedny ? $katalogNadrzedny->id : null;

        if ($katalog->save()) {
            return redirect()->back()->with('success', 'Katalog utworzony pomyślnie');
        } else {
            return redirect()->back()->with('error', 'Nazwa jest zajęta.');
        }
    }



    public function folderUsun(Request $request): \Illuminate\Http\JsonResponse
    {
        $folderId = $request->input('id');

        // Pobierz katalog do usunięcia
        $katalog = Katalog::find($folderId);

        if (!$katalog) {
            return response()->json(['error' => 'Katalog nie istnieje']);
        }

        //sprawdzam czy katalog który chce usunac posiada zawartosc
        $zawartosc = Katalog::where('rodzic_id', $katalog->id)->exists();

        if($zawartosc){
            $this->usunKatalogRekurencyjnie($katalog);
        }else{
            // Usuń główny katalog, ponieważ nie ma podkatalogów
            $katalog->delete();
        }



        return response()->json(['message' => 'Folder został usunięty']);
    }
    private function usunKatalogRekurencyjnie($katalog)
    {
        // Pobierz wszystkie podkatalogi tego katalogu
        $podkatalogi = Katalog::where('rodzic_id', $katalog->id)->get();

        // Usuń podkatalogi rekurencyjnie
        foreach ($podkatalogi as $podkatalog) {
            $this->usunKatalogRekurencyjnie($podkatalog);
        }

        // Usuń ten katalog
        $katalog->delete();
    }


    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'sciezka' => 'required',
            'nowaNazwa' => [
                'required',
                'regex:/^[a-zA-Z0-9\s\-_]+$/u',
                Rule::unique('katalogi', 'nazwa')
                    ->where(function ($query) use ($request) {
                        // Dodaj warunek, aby unikalna nazwa była w ramach tego samego rodzica
                        $query->where('rodzic_id', $request->input('nowaNazwa'));
                    })
            ],
        ], [
            'nowaNazwa.regex' => 'Nazwa katalogu może zawierać tylko litery, cyfry, spacje, myślniki i podkreślenia.',
        ]);

        $nowaNazwa = $request->input('nowaNazwa');
        $sciezka = $validatedData['sciezka'];

        // Dzielę ścieżkę na segmenty
        $segmenty = explode('/', trim($sciezka, '/'));

        if (count($segmenty) >= 2) {
            $przedostatniSegment = $segmenty[count($segmenty) - 2];
            $idPrzedostatni = Katalog::where('nazwa',$przedostatniSegment)->first();
            $id = $idPrzedostatni->id;

        }
        // Wyodrębniam ostatni segment
        $katalog = end($segmenty);



        $katalogGlowny = Katalog::where('nazwa', $katalog)
            ->where('rodzic_id', null) // Katalog główny nie ma rodzica_id
            ->first();


        $katalogi = Katalog::where('nazwa', $katalog)
            ->where('rodzic_id', '<>', null) // czy rodzic nie jest pusty
            ->get();

        // Aktualizuj nazwę katalogu głównego, jeśli istnieje w przypadku gdy chcemy aktulizowac nazwe katalogu glownego
        if ($katalogGlowny) {
            $katalogGlowny->nazwa = $nowaNazwa;
            $katalogGlowny->save();
        }else{
            // Aktualizuj nazwę podkatalogów
            foreach ($katalogi as $k) {
                if ($k->rodzic_id == $id) {
                    $k->nazwa = $nowaNazwa;

                    $k->save();

                }
            }
        }

        if ($katalogGlowny || count($katalogi) > 0) {
            return redirect()->back()->with('success', 'Nazwa katalogu została zaktualizowana.');
        } else {
            return redirect()->back()->with('error', 'Nie znaleziono katalogu do aktualizacji.');
        }
    }


        //pobrac nazwe katalogu nadrzednego
        //na podstawie nazwy pobrac jego id
        //zaktualizowac ten podkatalog ktory zgadza mu sie rodzic_id z id



}
