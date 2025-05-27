<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlastFreezerResource\Pages;
use App\Filament\Resources\BlastFreezerResource\RelationManagers;
use App\Models\BlastFreezer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlastFreezerResource extends Resource
{
    protected static ?string $model = BlastFreezer::class;
    protected static ?string $modelLabel = "Blast Freezer Logbook";
    protected static ?string $navigationGroup = "Logbook";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('batch_number')
                    ->maxLength(255),
                Forms\Components\TimePicker::make('time_in'),
                Forms\Components\Textarea::make('product_description')
                    ->columnSpanFull(),
                Forms\Components\Select::make('package_id')
                    ->relationship('package', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\TextInput::make('quality')
                    ->maxLength(255),
                Forms\Components\TextInput::make('initial_temp')
                    ->numeric(),
                Forms\Components\TextInput::make('freezer_temp')
                    ->numeric(),
                Forms\Components\Select::make('handle_by_id')
                    ->relationship('handleBy', 'name')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('initial_temp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('freezer_temp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('handleBy.name')
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
            'index' => Pages\ManageBlastFreezers::route('/'),
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
