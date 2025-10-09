<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RechargeLogResource\Pages;
use App\Models\RechargeLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class RechargeLogResource extends Resource
{
    protected static ?string $model = RechargeLog::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $slug = 'recharge-logs';

    // ✅ 导航标签和复数标签使用翻译
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
            ->filters([
                SelectFilter::make('channel_id')
                    ->label(__('filament.recharge_logs.fields.channel'))
                    ->options(
                        \App\Models\Channel::query()->pluck('name', 'id')->toArray()
                    ),
                SelectFilter::make('status')
                    ->label(__('filament.recharge_logs.fields.status'))
                    ->options([
                        0 => __('filament.recharge_logs.status_labels.pending'),
                        1 => __('filament.recharge_logs.status_labels.success'),
                        2 => __('filament.recharge_logs.status_labels.failed'),
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRechargeLogs::route('/'),
        ];
    }
}
