<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UtilityFacilityResource\Pages;
use App\Filament\Resources\UtilityFacilityResource\RelationManagers;
use App\Models\UtilityFacility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;

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
                            ->relationship('item', 'name')
                           ->disableOptionsWhenSelectedInSiblingRepeaterItems()
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
                Tables\Actions\ActionGroup::make([
                    Html2MediaAction::make('print')
                        ->print() // Enable print option
                        ->preview() // Enable preview option
                        ->filename(fn($record) => 'utility_and_facility_checklist'.$record->id ) // Custom file name
                        ->savePdf() // Enable save as PDF option
                        ->requiresConfirmation() // Show confirmation modal
                        ->pagebreak('section', ['css', 'legacy'])
                        ->orientation('portrait') // Portrait orientation
                        ->format('a4', 'mm') // A4 format with mm units
                        ->enableLinks() // Enable links in PDF
                        ->margin([0, 2, 0, 2]) // Set custom margins
                        ->modalWidth(MaxWidth::FitContent)
                        ->modalIcon('heroicon-o-printer')
                        ->icon('heroicon-o-printer')
                        ->slideOver()
                        ->content(fn($record) => view('output.utility', ['record' => $record])),
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
