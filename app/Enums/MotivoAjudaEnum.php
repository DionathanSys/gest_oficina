<?php

namespace App\Enums;

enum MotivoAjudaEnum: string
{
    case AJUDA_DE_CUSTO = 'Ref. Aj. Custo';
    case DOMINGO = 'Ref. Domingo(s)';
    case DIAS_BASE = 'Ref. Dias de Base';
    case MANOBRA = 'Ref. Manobra';
    case VIAGENS = 'Ref. Viagens em outro Caminhão';
    case FERISTA = 'Ref. Ferista';
    case QUEBRA_CAMINHAO = 'Ref. Quebra de caminhão';
    case TREINAMENTO_MOTORISTA = 'Ref. Treinamento de motorista';
    case MOTORISTA_EM_TREINAMENTO = 'Ref. motorista em treinamento';

    public function getOptions(): string
    {
        return match ($this) {
            self::AJUDA_DE_CUSTO => 'Ref. Ajuda de Custo',
            self::DOMINGO => 'Ref. Domingo',
            self::DIAS_BASE => 'Ref. Dias Base',
            self::MANOBRA => 'Ref. Manobra',
            self::VIAGENS => 'Ref. Viagens',
            self::FERISTA => 'Ref. Ferista',
            self::QUEBRA_CAMINHAO => 'Ref. Quebra de caminhão',
            self::TREINAMENTO_MOTORISTA => 'Ref. Treinamento de motorista',
            self::MOTORISTA_EM_TREINAMENTO => 'Ref. motorista em treinamento',
        };
    }

}
