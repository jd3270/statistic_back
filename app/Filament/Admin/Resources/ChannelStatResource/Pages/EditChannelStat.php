<?php

namespace App\Filament\Admin\Resources\ChannelStatResource\Pages;

use App\Filament\Admin\Resources\ChannelStatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChannelStat extends EditRecord
{
    protected static string $resource = ChannelStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
