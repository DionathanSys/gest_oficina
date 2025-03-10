<?php

namespace App\Services;

use App\Enums\StatusDiversos;
use App\Models\ItemOrdemServico;
use App\Models\OrdemServico;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;

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

    private static function notificaSucceso(string $title = '', string $body = ''): void
    {
        Notification::make()
            ->title($title . ' ' . now())
            ->body($body)
            ->sendToDatabase(User::find(4));
    }
    
}