<?php

namespace App\Enums;

enum TipoAnotacao: string
{
    case OBSERVACAO             = 'OBSERVAÇÃO';
    case ACOMPANHAMENTO         = 'ACOMPANHAMENTO';

    case MANUTENCAO             = 'MANUTENÇÃO';
    case INSPENCAO_PERIODICA    = 'INSPENÇÃO PERIODICA';
    case INSPENCAO_MANT         = 'INSPENÇÃO MANT';
    case INSPENCAO_PREVENTIVA   = 'INSPENÇÃO PREVENTIVA';
    
    case PNEU                   = 'PNEU';
    case MOV_PNEU               = 'MOV PNEU';
    case INSPENCAO_PNEU         = 'INSPENÇÃO PNEU';

    
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