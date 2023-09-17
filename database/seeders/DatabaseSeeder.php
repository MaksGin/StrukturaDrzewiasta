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

        ]);
        Katalog::create([
            'nazwa' => 'Dokumenty',

        ]);
        Katalog::create([
            'nazwa' => 'Ważne',
            'rodzic_id' => 2,

        ]);
        Katalog::create([
            'nazwa' => 'Wakacje',
            'rodzic_id' => 1,

        ]);


    }
}
