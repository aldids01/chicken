<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoolingVanResource\Pages;
use App\Filament\Resources\CoolingVanResource\RelationManagers;
use App\Models\CoolingVan;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;

class CoolingVanResource extends Resource
{
    protected static ?string $model = CoolingVan::class;
    protected static ?string $modelLabel = "Cooling Van Logbook";
    protected static ?string $navigationGroup = "Logbook";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('driver_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('supervisor_id')
                    ->relationship('supervisor', 'name'),
                Forms\Components\Repeater::make('items')
                    ->relationship('vanItems')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\DateTimePicker::make('departure_time')
                            ->required()
                            ->date(),
                        Forms\Components\TextInput::make('amount_products_carried')
                            ->required()
                            ->reactive()
                            ->live(onBlur: true)
                            ->default(0)
                            ->numeric(),
                        Forms\Components\TextInput::make('delivery_location')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('return_time')
                            ->required()
                            ->date(),
                        Forms\Components\TextInput::make('fuel_level')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('remark')
                            ->nullable()
                            ->maxLength(255),
                    ])->columns(6),
                Forms\Components\Placeholder::make('grand_total')
                    ->label('Total')
                    ->inlineLabel()
                    ->content(function (Forms\Get $get, Forms\Set $set){
                        $total = 0;
                        $repeaterItems = $get('items');
                        if (is_array($repeaterItems)) {
                            foreach ($repeaterItems as $repeaterRow) {
                                $total += (int) ($repeaterRow['amount_products_carried'] ?? 0);
                            }
                        }
                        $set('total', $total);
                        return Number::format($total, 2);
                    })->extraAttributes(['class' => 'font-bold text-right']),
                hidden::make('total')
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('driver_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->summarize(Sum::make())
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
                        ->filename(fn($record) => 'cooling_van_logbook'.$record->id ) // Custom file name
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
                        ->content(fn($record) => view('output.van', ['record' => $record])),
                    Tables\Actions\ViewAction::make()
                        ->modalWidth(MaxWidth::FitContent)
                        ->slideOver(),
                    Tables\Actions\EditAction::make()
                        ->modalWidth(MaxWidth::FitContent)
                        ->slideOver(),
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
            'index' => Pages\ManageCoolingVans::route('/'),
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
