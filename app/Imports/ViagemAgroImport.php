<?php

namespace App\Imports;

use App\Models\Motorista;
use App\Models\MotoristaViagem;
use App\Models\ViagemAgro;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpParser\Node\Expr\Cast;

class ViagemAgroImport
{

    protected $data = [];
    protected $referencia;
    protected $fechamento;

    public function __construct(Request $request)
    {
        $read = IOFactory::load($request->inputFile);
        $this->data = $read->getActiveSheet()->toArray();
        $this->referencia = $request->referencia;
        $this->fechamento = $request->fechamento;
        unset($this->data[0], $this->data[1], $this->data[2]);
    }

    public function store()
    {
        foreach ($this->data as $key => $row) {

            $data = DateTime::createFromFormat('d/m/Y', $row[2]);

            if ($data){
                $model = new ViagemAgro();
            
                $viagem = $model->create([
                    'referencia' => $this->referencia,
                    'fechamento' => $this->fechamento,
                    'nro_viagem' => $row[7],
                    'nro_nota' => $row[6],
                    'data' => $data->format('Y-m-d'),
                    'placa' => substr($row[4], 1, -1),
                    'km' => $row[5],
                    'frete' => str_replace(',', '', $row[14]),
                    'destino' => $row[15],
                    'local' => $row[20],
                    'vlr_cte' => str_replace(',', '', $row[16]),
                    'vlr_nfs' => str_replace(',', '', $row[17]),

                ]);
                
                $motorista = Motorista::where('codigo_sankhya', $row[8])->first();
                
                $dupla = null;
                
                if ($row[11]) {
                    $dupla = Motorista::where('codigo_sankhya', $row[11])->first();
                }
                
                $frete = str_replace(',', '', $row[14]);;
                $comissao = (int) $row[10];
                
                $motorista_viagem = new MotoristaViagem();
                $motorista_viagem->create([
                    'fechamento' => $this->fechamento,
                    'viagem_agro_id' => $viagem->id,
                    'nro_nota' => $row[6],
                    'motorista_id' => $motorista->id,
                    'motorista' => $motorista->nome,
                    'motorista_dupla_id' => $dupla ? $dupla->id : null,
                    'dupla' => $dupla ? $dupla->nome : null,
                    'frete' => $frete,
                    'comissao' => $row[10],
                    'vlr_comissao' => $frete * $comissao,
                ]);
            }
            
        }

        echo 'Finalizado';
    }

}
