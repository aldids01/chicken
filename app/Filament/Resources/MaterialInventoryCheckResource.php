<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialInventoryCheckResource\Pages;
use App\Filament\Resources\MaterialInventoryCheckResource\RelationManagers;
use App\Models\MaterialInventoryCheck;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;

class MaterialInventoryCheckResource extends Resource
{
    protected static ?string $model = MaterialInventoryCheck::class;

    protected static ?string $navigationGroup = "Checklist";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supervisor_id')
                    ->relationship('supervisor', 'name')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('item')
                    ->relationship('items')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('item_id')
                            ->relationship('item', 'name')
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\TextInput::make('available_quantity')
                            ->required()
                            ->reactive()
                            ->live(onBlur: true)
                            ->default(0)
                            ->numeric(),
                        Forms\Components\TextInput::make('remark'),
                    ])->columns(3),
                Forms\Components\Placeholder::make('grand_total_placeholder')
                    ->label('Total')
                    ->inlineLabel()
                    ->content(function (Forms\Get $get, Forms\Set $set){
                        $total = 0;
                        $repeaterItems = $get('item');
                        if (is_array($repeaterItems)) {
                            foreach ($repeaterItems as $repeaterRow) {
                                $total += (int) ($repeaterRow['available_quantity'] ?? 0);
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
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->summarize(Sum::make())
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
                        ->filename(fn($record) => 'material_inventory_checklist'.$record->id ) // Custom file name
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
                        ->content(fn($record) => view('output.inventory', ['record' => $record])),
                    Tables\Actions\ViewAction::make()
                        ->slideOver()
                        ->modalWidth(MaxWidth::FitContent),
                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->modalWidth(MaxWidth::FitContent),
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
            'index' => Pages\ManageMaterialInventoryChecks::route('/'),
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
