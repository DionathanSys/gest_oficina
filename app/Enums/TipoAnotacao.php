<?php

namespace App\Enums;

enum TipoAnotacao: string
{
    case MANUTENCAO = 'MANUTENÇÃO';
    case OBSERVACAO = 'OBSERVAÇÃO';
    case ACOMPANHAMENTO = 'ACOMPANHAMENTO';
    case INSPENCAO = 'INSPENÇÃO';
    case PNEU = 'PNEU';

    
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

}