<?php

namespace App\Enums;

enum Prioridade: string
{
    case BAIXA = 'BAIXA';
    case MEDIA = 'MEDIA';
    case ALTA = 'ALTA';
    case URGENTE = 'URGENTE';

    
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