<?php

namespace App\Filament\Admin\Resources;

use App\Models\RechargeLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use App\Filament\Admin\Resources\MyRechargeLogResource\Pages;

class MyRechargeLogResource extends Resource
{
    protected static ?string $model = RechargeLog::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $slug = 'my-recharge-logs';

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

        $filters = [
            SelectFilter::make('status')
                ->label(__('filament.recharge_logs.fields.status'))
                ->options([
                    0 => __('filament.recharge_logs.status_labels.pending'),
                    1 => __('filament.recharge_logs.status_labels.success'),
                    2 => __('filament.recharge_logs.status_labels.failed'),
                ]),
        ];

        // 普通用户可筛选自己关联的渠道
        $filters[] = SelectFilter::make('channel_id')
            ->label(__('filament.recharge_logs.fields.channel'))
            ->options(
                $user->availableChannels()->pluck('name', 'id')
            );

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('channel.name')->label(__('filament.recharge_logs.fields.channel')),
                Tables\Columns\TextColumn::make('amount')->label(__('filament.recharge_logs.fields.amount')),
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
            ->filters($filters)
            ->actions([])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();

        // 普通用户：仅显示自己关联渠道的充值记录
        return parent::getEloquentQuery()
            ->whereIn(
                'channel_id',
                $user->availableChannels()->pluck('id')
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyRechargeLogs::route('/'),
        ];
    }

    // 权限：只有普通用户可以查看
    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return !$user->isSuperAdmin();
    }
}
