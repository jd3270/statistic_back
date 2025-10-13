<?php

namespace App\Filament\Admin\Resources\MyChannelStatResource\Pages;

use App\Filament\Admin\Resources\MyChannelStatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyChannelStat extends EditRecord
{
    protected static string $resource = MyChannelStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
