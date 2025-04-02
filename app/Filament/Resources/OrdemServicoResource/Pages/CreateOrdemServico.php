<?php

namespace App\Filament\Resources\OrdemServicoResource\Pages;

use App\Filament\Resources\OrdemServicoResource;
use App\Models\OrdemServico;
use App\Services\OrdemServicoService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrdemServico extends CreateRecord
{
    protected static string $resource = OrdemServicoResource::class;

}
