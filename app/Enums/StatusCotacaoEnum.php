<?php

namespace App\Enums;

enum StatusCotacaoEnum: string
{
    case PENDENTE = 'Pendente';
    case APROVADO = 'Aprovado';
    case REPROVADO = 'Reprovado';
    case CANCELADO = 'Cancelado';
    case FECHADO = 'Fechado';
    case EM_ANDAMENTO = 'Em andamento';

    
    // public function getOptions(): string
    // {
    //     return match ($this) {
    //         self::PENDENTE => 'Pendente',
    //         self::APROVADO => 'Aprovado',
    //         self::REPROVADO => 'Reprovado',
    //         self::CANCELADO => 'Cancelado',
    //         self::FECHADO => 'Fechado',
    //         self::EM_ANDAMENTO => 'Em andamento',
    //     };
    // }

}