<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChannelStatResource\Pages;
use App\Models\ChannelStat;
use App\Models\Channel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ChannelStatResource extends Resource
{
    protected static ?string $model = ChannelStat::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $slug = 'channel-stats';

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

        // Define the base filters array
        $filters = [];

        // Only show the channel filter for the Super Admin (user ID 1)
        if ($user && $user->id === 1) {
            $filters[] = SelectFilter::make('channel_id')
                ->label(__('filament.channel_stats.fields.channel'))
                ->relationship('channel', 'name')
                ->searchable() // Allow searching within the filter options
                ->preload();    // Load options immediately
        }
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.channel_stats.fields.id'))
                    ->sortable(),

                // ✅ 显示频道名称（通过关联）
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChannelStats::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // 超级管理员查看所有
        if ($user && $user->id === 1) {
            return $query;
        }

        // 普通用户：通过 join pivot 表（完整库名）来过滤所属 channel
        return $query
            ->join('channels', 'channel_stats.channel_id', '=', 'channels.id')
            ->join('statistic_back.channel_user as cu', 'channels.id', '=', 'cu.channel_id')
            ->where('cu.user_id', $user->id)
            ->select('channel_stats.*')
            ->distinct();
    }
}
