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
use Illuminate\Support\Facades\Auth;
use Filament\Actions;

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
            Forms\Components\Select::make('role')
                ->label(__('filament.users.fields.role'))
                ->options([
                    1 => __('filament.roles.super_admin'),
                    2 => __('filament.roles.admin'),
                    3 => __('filament.roles.user'),
                ])
                ->default(3) // 默认为普通用户
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label(__('filament.users.fields.id'))->sortable(),
                Tables\Columns\TextColumn::make('name')->label(__('filament.users.fields.name'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label(__('filament.users.fields.email'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('role')->label('角色')->formatStateUsing(fn($state) => match ($state) { 1 => __('filament.roles.super_admin'), 2 => __('filament.roles.admin'), 3 => __('filament.roles.user'), default => '未知角色', }),
                Tables\Columns\TextColumn::make('created_at')->label(__('filament.users.fields.created_at'))->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => auth()->user()?->isSuperAdmin() ?? false),
            ])
            ->bulkActions([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn() => auth()->user()?->isSuperAdmin() ?? false),
            ]);
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

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    public static function canView($record = null): bool
    {
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? true;
    }

    public static function canEdit($record): bool
    {
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

}
