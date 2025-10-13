<?php

namespace App\Filament\Admin\Resources\MyRechargeLogResource\Pages;

use App\Filament\Admin\Resources\MyRechargeLogResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListMyRechargeLogs extends ListRecords
{
    protected static string $resource = MyRechargeLogResource::class;
}
