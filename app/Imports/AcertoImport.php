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
    protected $index;

    public function __construct(Request $request)
    {
        $read = IOFactory::load($request->inputFile);
        $this->data = $read->getActiveSheet()->toArray();
        $this->fechamento = $request->fechamento;
        $this->index = array_flip($this->data[2]);
        unset($this->data[0], $this->data[1], $this->data[2]);

    }

    public function store()
    {
        // extract($this->index);   
        // dump($this->index);
        // dump($this->data[3][$this->index['Vlr Fechamento']]);
        // dd($this->data);


        foreach ($this->data as $key => $row) {

            if ($row[2]) {

                (new Acerto())->query()->updateOrCreate(
                    [
                        'nro_acerto' => $row[1] // Atributo único ou que deve ser utilizado para a busca
                    ],
                    [
                        'fechamento' => $this->fechamento,
                        'nro_acerto' => $row[1],
                        'motorista_id' => Motorista::where('codigo_sankhya', $row[2])->value('id') ?? $this->motoristaCreate([
                            'nome' => $row[3],
                            'codigo_sankhya' => $row[2],
                        ]),
                        'motorista' => $row[3],
                        'vlr_fechamento' => str_replace(',', '', $row[15]) ?? 0,
                        'vlr_media' => str_replace(',', '', $row[5]) ?? 0,
                        'vlr_inss' => str_replace(',', '', $row[10]) ?? 0,
                        'vlr_irrf' => $row[11] ?? 0,
                        'vlr_manutencao' => 0,
                        'vlr_diferenca' => str_replace(',', '', $row[8]) ?? 0,
                        'vlr_comissao' => str_replace(',', '', $row[16]) - str_replace(',', '', $row[5]) ?? 0,
                        'fechado' => 0,
                        ]
                );
            }
        }

        echo 'Finalizado';
        
    }

    private function motoristaCreate(array $data): int
    {
        $motorista = Motorista::create($data);

        return $motorista->id;
    }
}
