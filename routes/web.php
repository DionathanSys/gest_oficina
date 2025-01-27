<?php

use App\Filament\Resources\VeiculoResource;
use App\Http\Controllers\ImportController;
use App\Models\AnotacaoVeiculo;
use App\Models\MotoristaViagem;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {

        $var = AnotacaoVeiculo::all()->first();
        return redirect(VeiculoResource::getUrl('edit', ['record' => $var->veiculo->id]));

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