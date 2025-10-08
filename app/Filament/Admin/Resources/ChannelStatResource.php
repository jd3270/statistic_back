<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChannelStatResource\Pages;
use App\Models\ChannelStat;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ChannelStatResource extends Resource
{
    protected static ?string $model = ChannelStat::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $slug = 'channel-stats';

    // ✅ 导航标签和复数标签使用翻译
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
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.channel_stats.fields.id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('channel')
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
            ->filters([
                SelectFilter::make('channel')
                    ->label(__('filament.channel_stats.fields.channel'))
                    ->options(fn () => ChannelStat::query()->distinct()->pluck('channel', 'channel')->toArray()),
            ])
            ->actions([]) // 只读：不显示行操作
            ->bulkActions([]); // 只读：不显示批量操作
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChannelStats::route('/'),
        ];
    }
}
