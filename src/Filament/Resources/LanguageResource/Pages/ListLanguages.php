<?php

namespace Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource\Pages;


use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
