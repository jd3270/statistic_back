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
                Tables\Columns\TextColumn::make('channel')
                    ->label(__('filament.recharge_logs.fields.channel'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('filament.recharge_logs.fields.amount')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.recharge_logs.fields.status'))
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => __('filament.recharge_logs.status_labels.pending'),
                        'success' => __('filament.recharge_logs.status_labels.success'),
                        'failed'  => __('filament.recharge_logs.status_labels.failed'),
                        default   => $state,
                    })
                    ->colors([
                        'warning' => fn($state) => $state === 'pending',
                        'success' => fn($state) => $state === 'success',
                        'danger'  => fn($state) => $state === 'failed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.recharge_logs.fields.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.recharge_logs.fields.status'))
                    ->options([
                        'pending' => __('filament.recharge_logs.status_labels.pending'),
                        'success' => __('filament.recharge_logs.status_labels.success'),
                        'failed'  => __('filament.recharge_logs.status_labels.failed'),
                    ]),
            ])
            ->actions([]) // 只读
            ->bulkActions([]); // 只读
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRechargeLogs::route('/'),
        ];
    }
}
