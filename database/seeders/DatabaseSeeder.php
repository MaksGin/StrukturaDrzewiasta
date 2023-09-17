<?php

namespace Database\Seeders;
use App\Models\Katalog;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Katalog::create([
            'nazwa' => 'Zdjęcia',
            // Dodaj inne kolumny i ich wartości
        ]);
        Katalog::create([
            'nazwa' => 'Dokumenty',
            // Dodaj inne kolumny i ich wartości
        ]);
        Katalog::create([
            'nazwa' => 'Ważne',
            // Dodaj inne kolumny i ich wartości
        ]);


    }
}
