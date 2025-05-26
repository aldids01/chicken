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

class MaterialInventoryCheckResource extends Resource
{
    protected static ?string $model = MaterialInventoryCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
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
