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
        $katalogi = Katalog::all();

        $DataJson = $katalogi->toJson();

        return response()->json($DataJson);

    }

    public function store(Request $request){

        $validatedData = $request->validate([
            'nazwa' => [
                'required',
                'regex:/^[a-zA-Z0-9\s\-_]+$/u',
                Rule::unique('katalogi', 'nazwa')->where(function ($query) use ($request) {
                    // Unikalna nazwa folderu tylko w ramach tego samego rodzica
                    return $query->where('rodzic_id', $request->input('rodzic_id'));
                }),
            ],
        ], [
            'nazwa.regex' => 'Nazwa katalogu może zawierać tylko litery, cyfry, spacje, myślniki i podkreślenia.',
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

        $katalog->save();

         return redirect()->back();
    }


    public function usunKatalog(Request $request)
    {
        $nazwaKatalogu = $request->input('nazwa');


        if(strpos($nazwaKatalogu, '/') === 0) {

            $segmenty = explode('/', trim($nazwaKatalogu, '/'));

            $katalog = Katalog::where('rodzic_id', null)->first();

            foreach ($segmenty as $segment) {

                $katalog = Katalog::where('nazwa', $segment)->where('rodzic_id', $katalog->nazwa)->first();

                if (!$katalog) {
                    return back()->with('error', 'Katalog lub ścieżka nie istnieje.');
                }
            }


            $this->usunKatalogRekurencyjnie($katalog);
            return redirect()->back();
        } else {

            $katalog = Katalog::where('nazwa', $nazwaKatalogu)->first();

            if (!$katalog) {
                return back()->with('error', 'Katalog nie istnieje.');
            }


            $this->usunKatalogRekurencyjnie($katalog);
            return redirect()->back();
        }


    }


    private function usunKatalogRekurencyjnie($katalog)
    {
        // pobieram wszystkie katalogi wybranego katalogu
        $podkatalogi = Katalog::where('rodzic_id', $katalog->id)->get();

        // usun ten katalog i jego zawartosc
        $katalog->delete();

        foreach ($podkatalogi as $podkatalog) {

            $this->usunKatalogRekurencyjnie($podkatalog);
        }
    }
}
