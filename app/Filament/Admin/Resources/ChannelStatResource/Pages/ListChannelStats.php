<?php

namespace App\Filament\Admin\Resources\ChannelStatResource\Pages;

use App\Filament\Admin\Resources\ChannelStatResource;
use Filament\Resources\Pages\ListRecords;

class ListChannelStats extends ListRecords
{
    protected static string $resource = ChannelStatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
