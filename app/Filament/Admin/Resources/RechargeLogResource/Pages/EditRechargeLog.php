<?php

namespace App\Filament\Admin\Resources\RechargeLogResource\Pages;

use App\Filament\Admin\Resources\RechargeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRechargeLog extends EditRecord
{
    protected static string $resource = RechargeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
