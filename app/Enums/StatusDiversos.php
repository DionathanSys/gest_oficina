<?php

namespace App\Enums;

enum StatusDiversos: string
{
    case PENDENTE = 'Pendente';
    case EXECUCAO = 'Execução';
    case VALIDAR = 'Validar';
    case CONCLUIDO = 'Concluido';
    case CANCELADO = 'Cancelado';

    
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