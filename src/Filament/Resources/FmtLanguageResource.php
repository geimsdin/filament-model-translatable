<?php

namespace  Unusualdope\FilamentModelTranslatable\Filament\Resources;

use Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource\Pages\CreateFmtLanguage;
use Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource\Pages\EditFmtLanguage;
use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource\Pages\ListFmtLanguages;

class FmtLanguageResource extends Resource
{
    protected static ?string $model = FmtLanguage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('iso_code')
                    ->required()
                    ->maxLength(4),
                Forms\Components\Toggle::make('is_default')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('iso_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_default')
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFmtLanguages::route('/'),
            'create' => CreateFmtLanguage::route('/create'),
            'edit' => EditFmtLanguage::route('/{record}/edit'),
        ];
    }
}
