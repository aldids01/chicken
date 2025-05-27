<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcessingResource\Pages;
use App\Filament\Resources\ProcessingResource\RelationManagers;
use App\Models\Processing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcessingResource extends Resource
{
    protected static ?string $model = Processing::class;
    protected static ?string $modelLabel = "Processing Logbook";
    protected static ?string $navigationGroup = "Logbook";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('batch_number')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('time_in'),
                Forms\Components\DateTimePicker::make('time_out'),
                Forms\Components\TextInput::make('birds_slaughtered')
                    ->numeric(),
                Forms\Components\TextInput::make('number_of_birds_processed')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('chilling_time'),
                Forms\Components\TextInput::make('portioning_done')
                    ->required(),
                Forms\Components\TextInput::make('whole_birds_package')
                    ->maxLength(255),
                Forms\Components\TextInput::make('wings')
                    ->numeric(),
                Forms\Components\TextInput::make('thighs')
                    ->numeric(),
                Forms\Components\TextInput::make('drumsticks')
                    ->numeric(),
                Forms\Components\TextInput::make('breast')
                    ->numeric(),
                Forms\Components\TextInput::make('gizzard')
                    ->numeric(),
                Forms\Components\TextInput::make('livers')
                    ->numeric(),
                Forms\Components\TextInput::make('necks')
                    ->numeric(),
                Forms\Components\TextInput::make('head_feet')
                    ->numeric(),
                Forms\Components\TextInput::make('heart')
                    ->numeric(),
                Forms\Components\TextInput::make('intestine_fat')
                    ->numeric(),
                Forms\Components\TextInput::make('total_package')
                    ->numeric(),
                Forms\Components\Select::make('package_id')
                    ->relationship('package', 'name'),
                Forms\Components\Select::make('package_officer_id')
                    ->relationship('packageOfficer', 'name'),
                Forms\Components\Select::make('package_supervisor_id')
                    ->relationship('packageSupervisor', 'name'),
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
                Tables\Columns\TextColumn::make('time_out')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birds_slaughtered')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_of_birds_processed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('chilling_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('portioning_done'),
                Tables\Columns\TextColumn::make('whole_birds_package')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wings')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('thighs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('drumsticks')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('breast')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gizzard')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('livers')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('necks')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('head_feet')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('heart')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('intestine_fat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_package')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('package.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('packageOfficer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('packageSupervisor.name')
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
            'index' => Pages\ManageProcessings::route('/'),
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
