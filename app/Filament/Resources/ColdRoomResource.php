<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColdRoomResource\Pages;
use App\Filament\Resources\ColdRoomResource\RelationManagers;
use App\Models\ColdRoom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ColdRoomResource extends Resource
{
    protected static ?string $model = ColdRoom::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('batch_number')
                    ->maxLength(255),
                Forms\Components\TimePicker::make('time_in'),
                Forms\Components\Textarea::make('product_description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('quality')
                    ->maxLength(255),
                Forms\Components\Select::make('package_id')
                    ->relationship('package', 'name'),
                Forms\Components\TextInput::make('blast_freezer')
                    ->numeric(),
                Forms\Components\TextInput::make('cold_room_temp')
                    ->numeric(),
                Forms\Components\Select::make('transferred_by_id')
                    ->columnSpanFull()
                    ->relationship('transferredBy', 'name'),
                Forms\Components\Textarea::make('remark')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batch_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('time_in')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quality')
                    ->searchable(),
                Tables\Columns\TextColumn::make('package.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('blast_freezer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cold_room_temp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transferredBy.name')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ManageColdRooms::route('/'),
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
