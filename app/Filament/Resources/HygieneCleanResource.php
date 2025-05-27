<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HygieneCleanResource\Pages;
use App\Filament\Resources\HygieneCleanResource\RelationManagers;
use App\Models\HygieneClean;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HygieneCleanResource extends Resource
{
    protected static ?string $model = HygieneClean::class;
    protected static ?string $modelLabel = "Hygiene and Cleanliness Checklist";

    protected static ?string $navigationGroup = 'Checklist';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supervisor_id')
                    ->relationship('supervisor', 'name')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('items')
                    ->columnSpanFull()
                    ->label('Items')
                    ->relationship('hygiene')
                    ->schema([
                        Forms\Components\Select::make('area_id')
                            ->required()
                            ->relationship('area', 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpanFull(),
                            ])
                            ->createOptionModalHeading('Create area'),
                        Forms\Components\ToggleButtons::make('status')
                            ->required()
                            ->inline()
                            ->grouped()
                            ->options([
                                'Clean' => 'Clean',
                                'Dirty' => 'Dirty',
                            ]),
                        Forms\Components\TextInput::make('remark')
                            ->maxLength(255)
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supervisor.name')
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
                        ->modalWidth(MaxWidth::FitContent),
                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->modalWidth(MaxWidth::FitContent),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])
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
            'index' => Pages\ManageHygieneCleans::route('/'),
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
