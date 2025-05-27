<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UtilityFacilityResource\Pages;
use App\Filament\Resources\UtilityFacilityResource\RelationManagers;
use App\Models\UtilityFacility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UtilityFacilityResource extends Resource
{
    protected static ?string $model = UtilityFacility::class;
    protected static ?string $modelLabel = 'Utility and Facility checklist';

    protected static ?string $navigationGroup ="Checklist";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supervisor_id')
                    ->columnSpanFull()
                    ->relationship('supervisor', 'name'),
                Forms\Components\Repeater::make('items')
                    ->columnSpanFull()
                    ->relationship('lineItems')
                    ->schema([
                       Forms\Components\Select::make('item_id')
                            ->required()
                            ->relationship('items', 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpanFull(),
                            ])->createOptionModalHeading('Create Utility and Facility Item'),
                        Forms\Components\ToggleButtons::make('status')
                            ->required()
                            ->inline()
                            ->grouped()
                            ->options([
                                'Okay' => 'Okay',
                                'Needs Attention' => 'Needs Attention',
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
            'index' => Pages\ManageUtilityFacilities::route('/'),
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
