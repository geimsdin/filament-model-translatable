<?php

namespace Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource\Pages;


use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Unusualdope\FilamentModelTranslatable\Filament\Resources\FmtLanguageResource;

class EditFmtLanguage extends EditRecord
{
    protected static string $resource = FmtLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
