<?php

namespace App\Filament\Resources\ProcessingResource\Pages;

use App\Filament\Resources\ProcessingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageProcessings extends ManageRecords
{
    protected static string $resource = ProcessingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth(MaxWidth::FitContent),
        ];
    }
}
