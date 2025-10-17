<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Models\Channel;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Support\Facades\DB;

class ChannelsRelationManager extends RelationManager
{
    protected static string $relationship = 'channels';
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Form 用于在 pivot 上添加额外字段，如果没有可为空
     */
    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // pivot 表额外字段可以放在这里
        ]);
    }

    /**
     * Table 显示用户已有的 channels，并支持 detach 和 attach
     */
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.channels.fields.id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('channel_code')
                    ->label(__('filament.channels.fields.channel_code'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.channels.fields.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('secret_key')
                    ->label(__('filament.channels.fields.secret_key'))
                    ->copyable(),
                Tables\Columns\IconColumn::make('status')
                    ->label(__('filament.channels.fields.status'))
                    ->boolean(),
            ])
            ->filters([])
            ->headerActions([
                AttachAction::make('attachChannel')
                    ->label(__('filament.users.actions.attach_channel'))
                    ->form([
                        Forms\Components\Select::make('channel_id')
                            ->label(__('filament.channels.fields.name'))
                            ->searchable()
                            ->options(function () {
                                // 取出所有已被使用的 channel_id
                                $usedIds = DB::table('channel_user')->pluck('channel_id')->toArray();

                                // 显示前 20 个未使用的频道
                                return Channel::query()
                                    ->whereNotIn('id', $usedIds)
                                    ->orderBy('id')
                                    ->limit(20)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getSearchResultsUsing(function (string $search) {
                                $usedIds = DB::table('channel_user')->pluck('channel_id')->toArray();

                                return Channel::query()
                                    ->whereNotIn('id', $usedIds)
                                    ->where('name', 'like', "%{$search}%")
                                    ->orderBy('id')
                                    ->limit(20)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn($value) => Channel::find($value)?->name)
                            ->placeholder(__('filament.users.placeholders.select_channel'))
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $this->ownerRecord->channels()->attach($data['channel_id']);
                    })
            ])
            ->actions([
                Tables\Actions\DetachAction::make(), // 允许 detach
            ])
            ->bulkActions([]);
    }
}
