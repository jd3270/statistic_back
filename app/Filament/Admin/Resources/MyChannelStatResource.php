<?php

namespace App\Filament\Admin\Resources;

use App\Models\ChannelStat;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\MyChannelStatResource\Pages;

class MyChannelStatResource extends Resource
{
    protected static ?string $model = ChannelStat::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $slug = 'my-channel-stats';
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.statistic');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.channel_stats.navigation_label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.channel_stats.plural_label');
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        $filters = [];

        // 普通用户也可以筛选自己的渠道
        $filters[] = SelectFilter::make('channel_id')
            ->label(__('filament.channel_stats.fields.channel'))
            ->options(
                $user->availableChannels()->pluck('name', 'id')
            );

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('channel.name')
                    ->label(__('filament.channel_stats.fields.channel'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('stat_date')
                    ->label(__('filament.channel_stats.fields.stat_date'))
                    ->date(),
                Tables\Columns\TextColumn::make('visit_count')
                    ->label(__('filament.channel_stats.fields.visit_count')),
                Tables\Columns\TextColumn::make('ip_count')
                    ->label(__('filament.channel_stats.fields.ip_count')),
                Tables\Columns\TextColumn::make('recharge_amount')
                    ->label(__('filament.channel_stats.fields.recharge_amount')),
                Tables\Columns\TextColumn::make('success_recharge_amount')
                    ->label(__('filament.channel_stats.fields.success_recharge_amount')),
                Tables\Columns\TextColumn::make('register_count')
                    ->label(__('filament.channel_stats.fields.register_count')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.channel_stats.fields.created_at'))
                    ->dateTime('Y-m-d H:i:s'),
            ])
            ->filters($filters)
            ->actions([])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        return parent::getEloquentQuery()
            ->whereIn('channel_id', $user->availableChannels()->pluck('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyChannelStats::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return !$user?->isSuperAdmin() ?? false;
    }

}
