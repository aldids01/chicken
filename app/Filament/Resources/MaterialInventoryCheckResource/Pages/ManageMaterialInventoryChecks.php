<?php

namespace App\Filament\Resources\MaterialInventoryCheckResource\Pages;

use App\Filament\Resources\MaterialInventoryCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageMaterialInventoryChecks extends ManageRecords
{
    protected static string $resource = MaterialInventoryCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::FitContent)
                ->slideOver(),
        ];
    }
}
