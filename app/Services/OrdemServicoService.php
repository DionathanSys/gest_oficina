<?php

namespace App\Services;

use App\Enums\StatusDiversos;
use App\Enums\StatusOrdemSankhya;
use App\Models\ItemOrdemServico;
use App\Models\OrdemServico;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class OrdemServicoService
{
    public static function create(array $data): OrdemServico
    {   
        $ordemServico = OrdemServico::create($data);
        
        if ($ordemServico){
            self::notificaSucceso('Ordem criada', 'Dt. ' . (Carbon::parse($ordemServico->data_abertura))->format('d/m/Y') . ' Placa: '. $ordemServico->veiculo->placa);
        }
        
        return $ordemServico;
    }

    public static function encerrarOrdem(OrdemServico $ordemServico): void
    {
        $ordemServico->update([
            'status' => StatusDiversos::CONCLUIDO,
        ]);

        self::notificaSucceso('Ordem Encerrada', 'Ordem encerrada. Placa ' . $ordemServico->veiculo->placa . 'Nro. ' . $ordemServico->nro_ordem ?? 'N/A');
    }

    public function delete(OrdemServico $ordemServico): void
    {
        $ordemServico->delete();
    }

    public static function addItem(OrdemServico $ordemServico, array $data): ItemOrdemServico
    {
        $data['status'] = StatusDiversos::PENDENTE;
        $item = $ordemServico->itens()->create($data);

        if ($item){
            self::notificaSucceso('Novo Item', 'Placa ' . $ordemServico->veiculo->placa . ', Item: ' . $item->id);
        }

        return $item;
    }

    public static function setNroOrdem(OrdemServico $ordemServico): true
    {

        $ordemServico->update([
            'status_sankhya' => StatusOrdemSankhya::ABERTO,
        ]);

        return true;
    }

    public static function updateStatusOrdem(Collection $ordensServico, StatusDiversos $status)
    {
        $ordensServico->each(function(OrdemServico $ordem) use ($status) {
            $ordem->update([
                'status' => $status,
            ]);
        });
    }
    
    public static function updateStatusSankhya(Collection $ordensServico, StatusOrdemSankhya $status)
    {
        $ordensServico->each(function(OrdemServico $ordem) use ($status) {
            $ordem->update([
                'status_sankhya' => $status,
            ]);
        });
    }

    public static function encerrarOrdemSankhya(OrdemServico $ordemServico): void
    {
        $ordemServico->update([
            'status_sankhya' => StatusOrdemSankhya::CONCLUIDO,
        ]);
    }

    public static function encerrarServico(Collection $itens): true
    {
        $itens->each(function (ItemOrdemServico $item) {
            $item->update([
                'status' => StatusDiversos::CONCLUIDO,
            ]);
            
        });
        
        return true;
    }

    private static function notificaSucceso(string $title = '', string $body = ''): void
    {
        Notification::make()
            ->title($title . ' ' . (Carbon::parse(now()))->format('d/m/Y H:i'))
            ->body($body)
            ->sendToDatabase(User::find(4));
    }
    
}