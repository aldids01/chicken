<?php

namespace App\Filament\Resources\PackageTypeResource\Pages;

use App\Filament\Resources\PackageTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManagePackageTypes extends ManageRecords
{
    protected static string $resource = PackageTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::FitContent)
                ->slideOver(),
        ];
    }
}
