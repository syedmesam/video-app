<?php

use App\Models\Upload;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::post('/upload', [App\Http\Controllers\HomeController::class, 'uploadVideo'])->name('upload');
    Route::get('/get/all', function () {
        $videos = Upload::latest()->get();

        return view('videos')->with(['videos' => $videos]);
    })->name('all.videos');
});
