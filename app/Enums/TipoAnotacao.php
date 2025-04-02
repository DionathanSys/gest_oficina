<?php

namespace App\Enums;

enum TipoAnotacao: string
{
    case OBSERVACAO             = 'OBSERVAÇÃO';
    case ACOMPANHAMENTO         = 'ACOMPANHAMENTO';

    case MANUTENCAO             = 'MANUTENÇÃO';
    case INSPECAO_PERIODICA     = 'INSPEÇÃO PERIODICA';
    case INSPECAO_MANT          = 'INSPEÇÃO MANT';
    case INSPECAO_PREVENTIVA    = 'INSPEÇÃO PREVENTIVA';
    
    case PNEU                   = 'PNEU';
    case MOV_PNEU               = 'MOV PNEU';
    case INSPECAO_PNEU          = 'INSPEÇÃO PNEU';

    
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