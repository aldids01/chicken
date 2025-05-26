<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BirdReceivedResource\Pages;
use App\Filament\Resources\BirdReceivedResource\RelationManagers;
use App\Models\BirdReceived;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BirdReceivedResource extends Resource
{
    protected static ?string $model = BirdReceived::class;
    protected static ?string $recordTitleAttribute = 'batch_number';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TimePicker::make('time_of_arrival'),
                Forms\Components\TextInput::make('batch_number')
                    ->maxLength(255),
                Forms\Components\Select::make('supervisor_id')
                    ->relationship('supervisor', 'name'),
                Forms\Components\TextInput::make('vehicle_no')
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_of_birds_delivered')
                    ->numeric(),
                Forms\Components\TextInput::make('number_of_birds_dead_on_arrival')
                    ->numeric(),
                Forms\Components\Select::make('recovery_officer_id')
                    ->relationship('recoveryOfficer', 'name')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('remark')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('time_of_arrival')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_of_birds_delivered')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_of_birds_dead_on_arrival')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recoveryOfficer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->slideOver()
                        ->modalWidth(MaxWidth::Small),
                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->modalWidth(MaxWidth::Small),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBirdReceiveds::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
