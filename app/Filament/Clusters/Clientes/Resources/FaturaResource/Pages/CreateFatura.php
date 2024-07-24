<?php

namespace App\Filament\Clusters\Clientes\Resources\FaturaResource\Pages;

use App\Filament\Clusters\Clientes\Resources\FaturaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFatura extends CreateRecord
{
    protected static string $resource = FaturaResource::class;
}
