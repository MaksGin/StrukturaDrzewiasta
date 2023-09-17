<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/',[KatalogController::class,'index'])->name('welcome');

Route::post('/katalog/dodaj',[KatalogController::class,'store'])->name('katalog.dodaj');
Route::get('/pobierz-katalogi',[KatalogController::class,'getKatalogi']);


Route::delete('/usun-katalog',[KatalogController::class,'usunKatalog'])->name('katalog.usun');
Route::delete('/usun-folder',[KatalogController::class,'folderUsun'])->name('folder.usun');
Route::put('/katalog/update', [KatalogController::class, 'update'])->name('katalog.edytuj');
Route::get('/sortuj-katalogi', [KatalogController::class, 'sortujKatalogi'])->name('katalog.sortuj');
