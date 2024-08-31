<?php

namespace App\Http\Controllers;

use App\Imports\AcertoImport;
use App\Imports\MotoristaImport;
use App\Imports\PrSegurancaImport;
use App\Imports\ViagemAgroImport;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        return view('importFile');
    }

    public function store(Request $request)
    {
        $controle = $request->controle;

        if ($controle == 'viagem.agro'){
            $import = (new ViagemAgroImport($request))->store();
        }

        if ($controle == 'motorista.store'){
            $import = (new MotoristaImport($request))->store();
        }
        
        if ($controle == 'acerto.store'){
            $import = (new AcertoImport($request))->store();
        }

        if ($controle == 'pr_seguranca.store'){
            $import = (new PrSegurancaImport($request))->store();
        }

        

    }
}
