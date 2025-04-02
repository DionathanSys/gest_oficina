<?php

use App\Http\Controllers\ImportController;
use App\Models\Acerto;
use App\Models\MotoristaViagem;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {

    $a = Acerto::find(618);
    dd($a->getComplemento());
        // return view('Protocolo.modelo');
});

Route::get('/agro/viagem/import', [ImportController::class, 'index'])->name('file.import');

Route::post('/agro/viagem/store', [ImportController::class, 'store'])->name('file.store');

Route::get('/ajuste', function(){
        $registros = MotoristaViagem::where('dupla', '!=', null)->get();
        // dd($registros);
        $data = $registros->each(function($registro){
                // dd($registro);
                $motorista = $registro->dupla;
                $registro->dupla = $registro->motorista;
                $registro->motorista = $motorista;
                unset($registro->created_at);
                unset($registro->updated_at);
                unset($registro->id);
        });

        DB::table('motoristas_viagem')->insert($data->toArray());
});
