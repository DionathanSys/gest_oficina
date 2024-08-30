<?php

namespace App\Http\Controllers;

use App\Imports\MotoristaImport;
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

        

    }
}
