<?php

use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
   
        return view('Protocolo.modelo');
});

Route::get('/agro/viagem/import', [ImportController::class, 'index'])->name('file.import');

Route::post('/agro/viagem/store', [ImportController::class, 'store'])->name('file.store');