<?php

namespace App\Filament\Admin\Resources\RechargeLogResource\Pages;

use App\Filament\Admin\Resources\RechargeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRechargeLogs extends ListRecords
{
    protected static string $resource = RechargeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
