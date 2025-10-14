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
        // 获取当前登录用户
        $user = Auth::user();

        // ✅ 仅 SuperAdmin 可见「创建」按钮
        return $user && $user->isSuperAdmin()
            ? [
                Actions\CreateAction::make()
                    ->label(__('filament.channels.actions.create'))
                    ->icon('heroicon-o-plus-circle')
                    ->color('success'),
            ]
            : [];
    }
}
