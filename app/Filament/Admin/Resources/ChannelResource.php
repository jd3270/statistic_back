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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ChannelResource extends Resource
{
    protected static ?string $model = Channel::class;
    protected static ?string $navigationGroup = 'Statistic';
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $slug = 'channels';
    protected static ?int $navigationSort = 98;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.setting');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.channels.navigation_label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.channels.plural_label');
    }

    /**
     * 这里重写查询，根据当前用户过滤渠道
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user?->isSuperAdmin()) {
            return $query;
        }

        // 普通用户：使用 join 过滤
        return $query->whereIn('id', function ($sub) use ($user) {
            $sub->select('channel_id')
                ->from('statistic_back.channel_user')
                ->where('user_id', $user->id);
        });
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('channel_code')
                ->label(__('filament.channels.fields.channel_code'))
                ->required()
                ->unique(ignoreRecord: true),

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
        $user = Auth::user();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.channels.fields.id'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('channel_code')
                    ->label(__('filament.channels.fields.channel_code'))
                    ->sortable()
                    ->searchable(),

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
                    ->label(__('filament.channels.actions.delete'))
                    ->visible(fn() => $user?->isSuperAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChannels::route('/'),
            'create' => Pages\CreateChannel::route('/create'),
            'edit' => Pages\EditChannel::route('/{record}/edit'),
        ];
    }
}
