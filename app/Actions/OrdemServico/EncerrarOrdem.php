<?php

namespace App\Actions\OrdemServico;

use App\Enums\StatusDiversos;
use App\Models\OrdemServico;
use Filament\Notifications\Notification;

class EncerrarOrdem
{

    public static function execute(OrdemServico $ordemServico, $dataEncerramento = null)
    {
        if ($ordemServico->status == StatusDiversos::CONCLUIDO || $ordemServico->status == StatusDiversos::CANCELADO) {
            self::notificarErro('A ordem de serviço já está encerrada ou cancelada.');
            return false;
        }

        if ($ordemServico->itens->isEmpty()) {
            self::notificarErro('A ordem de serviço não possui itens.');
            return false;
        }

        $ordemServico->each(function ($item) {
            $item->update(
                [
                    'status' => StatusDiversos::CONCLUIDO
                ]
            );
        });

        $ordemServico->update([
            'status' => StatusDiversos::CONCLUIDO,
            'data_encerramento' => $dataEncerramento ?? now(),
        ]);

        return true;
    }

    private static function notificarSucesso(string $body)
    {
        Notification::make()
            ->title('Ordem de Serviço')
            ->body($body)
            ->success()
            ->send();
    }

    private static function notificarErro(string $body)
    {
        Notification::make()
            ->title('Ordem de Serviço')
            ->body($body)
            ->danger()
            ->send();
    }
}
