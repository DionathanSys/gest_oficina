<?php

namespace App\Enums;

enum StatusOrdemSankhya: string
{
    case PENDENTE   = 'PENDENTE';
    case ABERTO     = 'ABERTO';
    case CONCLUIDO  = 'CONCLUÍDO';
    case CANCELADO  = 'CANCELADO';

    public static function toSelectArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($item) => [$item->value => $item->name])
            ->toArray();
    }

}