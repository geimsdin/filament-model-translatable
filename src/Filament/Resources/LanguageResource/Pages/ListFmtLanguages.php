<?php

namespace Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource\Pages;


use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Unusualdope\FilamentModelTranslatable\Filament\Resources\FmtLanguageResource;

class ListFmtLanguages extends ListRecords
{
    protected static string $resource = FmtLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
