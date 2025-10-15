<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChannelStatResource\Pages;
use App\Models\ChannelStat;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('channel.name')
                        ->label(__('filament.channel_stats.fields.channel'))
                        ->disabled(),

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

                    Forms\Components\DateTimePicker::make('updated_at')
                        ->label(__('filament.channel_stats.fields.updated_at'))
                        ->disabled(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        $filters = [];

        if ($user?->isSuperAdmin()) {
            $filters[] = SelectFilter::make('channel_id')
                ->label(__('filament.channel_stats.fields.channel'))
                ->relationship('channel', 'name')
                ->searchable()
                ->preload();
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.channel_stats.fields.id'))
                    ->sortable(),

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
                Tables\Actions\ViewAction::make()
                    ->slideOver(), // ✅ 使用 slideOver 查看详情
            ])
            ->bulkActions([])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChannelStats::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderByDesc('id');
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }
}
