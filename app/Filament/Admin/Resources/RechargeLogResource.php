<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RechargeLogResource\Pages;
use App\Models\RechargeLog;
use App\Models\Channel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class RechargeLogResource extends Resource
{
    protected static ?string $model = RechargeLog::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $slug = 'recharge-logs';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.statistic');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.recharge_logs.navigation_label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.recharge_logs.plural_label');
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        // Define the base filters array
        $filters = [
            // The 'status' filter is visible to everyone
            SelectFilter::make('status')
                ->label(__('filament.recharge_logs.fields.status'))
                ->options([
                    0 => __('filament.recharge_logs.status_labels.pending'),
                    1 => __('filament.recharge_logs.status_labels.success'),
                    2 => __('filament.recharge_logs.status_labels.failed'),
                ]),
        ];

        // Only show the channel filter for the Super Admin (user ID 1)
        if ($user && $user->id === 1) {
            // Add the channel filter to the front of the filters array
            array_unshift(
                $filters,
                SelectFilter::make('channel_id')
                    ->label(__('filament.recharge_logs.fields.channel'))
                    // Use relationship for better performance/standard practice
                    ->relationship('channel', 'name')
                    ->searchable()
                    ->preload()
            );
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.recharge_logs.fields.id'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('channel.name')
                    ->label(__('filament.recharge_logs.fields.channel'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('filament.recharge_logs.fields.amount')),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.recharge_logs.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn($state) => match ((int) $state) {
                        0 => __('filament.recharge_logs.status_labels.pending'),
                        1 => __('filament.recharge_logs.status_labels.success'),
                        2 => __('filament.recharge_logs.status_labels.failed'),
                        default => 'Unknown',
                    })
                    ->color(fn($state) => match ((int) $state) {
                        0 => 'warning',
                        1 => 'success',
                        2 => 'danger',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.recharge_logs.fields.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->filters($filters) // Apply the conditionally populated $filters array
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRechargeLogs::route('/'),
        ];
    }

    /**
     * 修复：使用 JOIN 替代 whereHas 跨数据库过滤
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // 管理员 (ID = 1) 查看所有记录
        if ($user && $user->id === 1) {
            return $query;
        }

        // 普通用户：仅查看自己关联渠道的充值记录
        return $query
            ->join('channels', 'recharge_logs.channel_id', '=', 'channels.id')
            ->join('statistic_back.channel_user as cu', 'channels.id', '=', 'cu.channel_id')
            ->where('cu.user_id', $user->id)
            ->select('recharge_logs.*')
            ->distinct();
    }
}
