<?php

namespace App\Filament\Pages;

use App\Filament\Resources\UserResource\Widgets\RecentActivity;
use Filament\Pages\Dashboard;
use Filament\Widgets\AccountWidget;

class Home extends Dashboard
{
    protected int | string | array $columnSpan = 'full';
    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
    protected function getHeaderWidgets(): array
    {
        return [
          AccountWidget::class,
          RecentActivity::class,
        ];
    }
}
