<?php

namespace Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource\Pages;


use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource;

class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
