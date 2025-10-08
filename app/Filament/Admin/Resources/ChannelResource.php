<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChannelResource\Pages;
use App\Models\Channel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ChannelResource extends Resource
{
    protected static ?string $model = Channel::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $slug = 'channels';

    // ✅ 导航标签使用翻译
    public static function getNavigationLabel(): string
    {
        return __('filament.channels.navigation_label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.channels.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('filament.channels.fields.name'))
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('secret_key')
                ->label(__('filament.channels.fields.secret_key'))
                ->readOnly()
                ->default(fn() => Str::random(32))
                ->suffixAction(
                    Forms\Components\Actions\Action::make('refresh_key')
                        ->icon('heroicon-o-arrow-path')
                        ->tooltip(__('filament.channels.actions.refresh_key'))
                        ->action(function ($state, Forms\Set $set) {
                            $set('secret_key', Str::random(32));
                        })
                ),

            Forms\Components\Toggle::make('status')
                ->label(__('filament.channels.fields.status'))
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.channels.fields.id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.channels.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('secret_key')
                    ->label(__('filament.channels.fields.secret_key'))
                    ->copyable(),
                Tables\Columns\IconColumn::make('status')
                    ->label(__('filament.channels.fields.status'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.channels.fields.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label(__('filament.channels.filters.status'))
                    ->trueLabel(__('filament.channels.filters.active'))
                    ->falseLabel(__('filament.channels.filters.inactive')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('filament.channels.actions.edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('filament.channels.actions.delete')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListChannels::route('/'),
            'create' => Pages\CreateChannel::route('/create'),
            'edit'   => Pages\EditChannel::route('/{record}/edit'),
        ];
    }
}
