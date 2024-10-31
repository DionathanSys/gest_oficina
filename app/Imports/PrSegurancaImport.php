<?php

namespace App\Imports;

use App\Models\Acerto;
use App\Models\Motorista;
use App\Models\PrSeguranca;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PrSegurancaImport
{

    protected $data = [];
    protected $fechamento;

    public function __construct(Request $request)
    {
        $read = IOFactory::load($request->inputFile);
        $this->data = $read->getActiveSheet()->toArray();
        $this->fechamento = $request->fechamento;
        unset($this->data[0]);
    }

    public function store()
    {
        // dd($this->data);
        foreach ($this->data as $key => $row) {

            if($row[0]){
                
                $acerto_id = (Acerto::where('nro_acerto', $row[0])->first())->id;
                $premio = str_replace(',', '.', str_replace('R$', '', $row[2]));

                (new PrSeguranca())->firstOrCreate(
                    [
                        'acerto_id' => $acerto_id // Atributo único ou que deve ser utilizado para a busca
                    ],
                    [
                        'acerto_id' => $acerto_id,
                        'premio' => $premio ?? 0,
                    ]
                );
            }
        }

        echo 'Finalizado';
        
    }
}
