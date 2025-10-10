<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Models\Channel;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;

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
                            ->options(function () {
                                // 当前 User
                                $user = $this->ownerRecord;

                                // 获取当前用户已关联的 channel_id
                                $usedIds = $user->channels()->pluck('channels.id')->toArray();

                                // 返回未关联的 channels
                                return Channel::query()
                                    ->whereNotIn('id', $usedIds)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        // $this->ownerRecord 是当前用户
                        $this->ownerRecord->channels()->attach($data['channel_id']);
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(), // 允许 detach
            ])
            ->bulkActions([]);
    }
}
