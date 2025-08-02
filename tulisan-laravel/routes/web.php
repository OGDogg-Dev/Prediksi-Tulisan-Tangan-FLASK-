<?php

use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('landing');
})->name('home');

// OCR page
use App\Http\Controllers\OcrController;
Route::match(['get', 'post'], '/tulisan', [OcrController::class, 'index'])->name('ocr.index');
