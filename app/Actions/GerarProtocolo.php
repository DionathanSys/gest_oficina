<?php

namespace App\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class GerarProtocolo 
{
    public static function exec (Collection $records) 
    {
        
        $data = $records->map(function ($record) { 
            $record->update(['envio' => Carbon::now()]);
            return ['fornecedor' => $record->fornecedor->nome, 'nro_documento' => $record->nro_documento];
        })->toArray();
        
        // Storage::put('public/pdfs/teste2.pdf', $pdf->output());

        return true;

    }
}