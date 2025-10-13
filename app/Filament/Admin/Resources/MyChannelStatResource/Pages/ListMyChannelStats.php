<?php

namespace App\Filament\Admin\Resources\MyChannelStatResource\Pages;

use App\Filament\Admin\Resources\MyChannelStatResource;
use Filament\Resources\Pages\ListRecords;

class ListMyChannelStats extends ListRecords
{
    protected static string $resource = MyChannelStatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
