<?php

namespace App\Imports;

use App\Models\Motorista;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MotoristaImport
{

    protected $data = [];

    public function __construct(Request $request)
    {
        $read = IOFactory::load($request->inputFile);
        $this->data = $read->getActiveSheet()->toArray();
        unset($this->data[0], $this->data[1], $this->data[2]);
    }

    public function store()
    {
        $refImportacao = 1;

        foreach ($this->data as $key => $row) {

            if ($row[8]) {
                $model = new Motorista();

                $model->firstOrCreate(
                    [
                        'codigo_sankhya' => $row[8] // Atributo único ou que deve ser utilizado para a busca
                    ],
                    [
                        'nome' => $row[9], // Atributos para criar o registro, caso não exista
                    ]
                );
            }


            if ($row[11]) {

                $dupla = new Motorista();

                $dupla->firstOrCreate(
                    [
                        'codigo_sankhya' => $row[11] // Atributo único ou que deve ser utilizado para a busca
                    ],
                    [
                        'nome' => $row[12], // Atributos para criar o registro, caso não exista
                    ]
                );
            }
        }

        echo 'Finalizado';
        
    }
}
