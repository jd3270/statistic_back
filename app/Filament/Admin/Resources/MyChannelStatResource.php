<?php

namespace App\Filament\Admin\Resources;

use App\Models\ChannelStat;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('channel_name')
                        ->label(__('filament.channel_stats.fields.channel'))
                        ->disabled()
                        ->afterStateHydrated(function ($component, $state, $record) {
                            $component->state($record->channel?->name ?? '');
                        }),

                    Forms\Components\DatePicker::make('stat_date')
                        ->label(__('filament.channel_stats.fields.stat_date'))
                        ->disabled(),

                    Forms\Components\TextInput::make('visit_count')
                        ->label(__('filament.channel_stats.fields.visit_count'))
                        ->disabled(),

                    Forms\Components\TextInput::make('ip_count')
                        ->label(__('filament.channel_stats.fields.ip_count'))
                        ->disabled(),

                    Forms\Components\TextInput::make('recharge_amount')
                        ->label(__('filament.channel_stats.fields.recharge_amount'))
                        ->disabled(),

                    Forms\Components\TextInput::make('recharge_count')
                        ->label(__('filament.channel_stats.fields.recharge_count'))
                        ->disabled(),

                    Forms\Components\TextInput::make('success_recharge_amount')
                        ->label(__('filament.channel_stats.fields.success_recharge_amount'))
                        ->disabled(),

                    Forms\Components\TextInput::make('success_recharge_count')
                        ->label(__('filament.channel_stats.fields.success_recharge_count'))
                        ->disabled(),

                    Forms\Components\TextInput::make('register_count')
                        ->label(__('filament.channel_stats.fields.register_count'))
                        ->disabled(),

                    Forms\Components\TextInput::make('login_count')
                        ->label(__('filament.channel_stats.fields.login_count'))
                        ->disabled(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        // ===== 筛选器 =====
        $filters = [
            // 日期区间筛选器
            Filter::make('stat_date')
                ->label(__('filament.channel_stats.fields.stat_date'))
                ->form([
                    DatePicker::make('start_date')
                        ->label(__('filament.channel_stats.filters.start_date'))
                        ->default(now()->subDays(7)),
                    DatePicker::make('end_date')
                        ->label(__('filament.channel_stats.filters.end_date'))
                        ->default(now()),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['start_date'], fn($q) => $q->whereDate('stat_date', '>=', $data['start_date']))
                        ->when($data['end_date'], fn($q) => $q->whereDate('stat_date', '<=', $data['end_date']));
                }),

            // 用户可选频道
            SelectFilter::make('channel_id')
                ->label(__('filament.channel_stats.fields.channel'))
                ->options($user->availableChannels()->pluck('name', 'id'))
                ->searchable()
                ->preload(),
        ];

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('channel.name')
                    ->label(__('filament.channel_stats.fields.channel'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('stat_date')
                    ->label(__('filament.channel_stats.fields.stat_date'))
                    ->date()
                    ->sortable(),

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
            ])
            ->filters($filters)
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
            ])
            ->bulkActions([])
            ->defaultSort('id', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        return parent::getEloquentQuery()
            ->whereIn('channel_id', $user->availableChannels()->pluck('id'))
            ->orderByDesc('id');
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
