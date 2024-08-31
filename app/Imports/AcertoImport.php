<?php

namespace App\Imports;

use App\Models\Acerto;
use App\Models\Motorista;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AcertoImport
{

    protected $data = [];
    protected $fechamento;

    public function __construct(Request $request)
    {
        $read = IOFactory::load($request->inputFile);
        $this->data = $read->getActiveSheet()->toArray();
        $this->fechamento = $request->fechamento;
        unset($this->data[0], $this->data[1], $this->data[2]);
    }

    public function store()
    {
        // dd($this->data);
        foreach ($this->data as $key => $row) {

            if ($row[2]) {

                (new Acerto())->firstOrCreate(
                    [
                        'nro_acerto' => $row[1] // Atributo único ou que deve ser utilizado para a busca
                    ],
                    [
                        'fechamento' => $this->fechamento,
                        'nro_acerto' => $row[1],
                        'motorista_id' => Motorista::where('codigo_sankhya', $row[2])->value('id'),
                        'motorista' => $row[3],
                        'vlr_fechamento' => str_replace(',', '', $row[15]) ?? 0,
                        'vlr_media' => str_replace(',', '', $row[5]) ?? 0,
                        'vlr_inss' => str_replace(',', '', $row[10]) ?? 0,
                        'vlr_irrf' => $row[11] ?? 0,
                        'vlr_manutencao' => 0,
                        'vlr_diferenca' => str_replace(',', '', $row[8]) ?? 0,
                        'vlr_comissao' => str_replace(',', '', $row[16]) - str_replace(',', '', $row[5]) ?? 0,
                        ]
                );
            }
        }

        echo 'Finalizado';
        
    }
}
