<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers\ChannelsRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $slug = 'users';
    protected static ?int $navigationSort = 99;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.setting');
    }
    public static function getNavigationLabel(): string
    {
        return __('filament.users.navigation_label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.users.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('filament.users.fields.name'))
                ->required(),
            Forms\Components\TextInput::make('email')
                ->label(__('filament.users.fields.email'))
                ->email()
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('password')
                ->label(__('filament.users.fields.password'))
                ->password()
                ->required(fn($livewire) => $livewire instanceof Pages\CreateUser),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label(__('filament.users.fields.id'))->sortable(),
                Tables\Columns\TextColumn::make('name')->label(__('filament.users.fields.name'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label(__('filament.users.fields.email'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('filament.users.fields.created_at'))->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            ChannelsRelationManager::class,
        ];
    }

    // 🔹 只显示超级管理员（id=1）
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', 1);
    }
}
