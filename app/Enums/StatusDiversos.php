<?php

namespace App\Enums;

enum StatusDiversos: string
{
    case PENDENTE   = 'PENDENTE';
    case EXECUCAO   = 'EXECUÇÃO';
    case VALIDAR    = 'VALIDAR';
    case CONCLUIDO  = 'CONCLUÍDO';
    case CANCELADO  = 'CANCELADO';

    
    // public function getOptions(): string
    // {
    //     return match ($this) {
    //         self::PENDENTE => 'Pendente',
    //         self::EXECUCAO => 'Aprovado',
    //         self::VALIDAR => 'Reprovado',
    //         self::CANCELADO => 'Cancelado',
    //         self::FECHADO => 'Fechado',
    //         self::EM_ANDAMENTO => 'Em andamento',
    //     };
    // }

    public static function toSelectArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($item) => [$item->value => $item->name])
            ->toArray();
    }

}