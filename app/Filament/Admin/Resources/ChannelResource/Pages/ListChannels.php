<?php

namespace App\Filament\Admin\Resources\ChannelResource\Pages;

use App\Filament\Admin\Resources\ChannelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListChannels extends ListRecords
{
    protected static string $resource = ChannelResource::class;

    protected function getHeaderActions(): array
    {
        $user = Auth::user();

        if ($user?->isSuperAdmin()) {
            return [
                Actions\CreateAction::make()
                    ->label(__('filament.channels.actions.create')),
            ];
        }

        return [];
    }
}
