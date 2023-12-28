<?php

namespace Unusualdope\FilamentModelTranslatable;


use Filament\Panel;
use Filament\PanelProvider;
use Unusualdope\FilamentModelTranslatable\Filament\Resources\LanguageResource;

class FmtPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('filament-model-translatable')
            ->path('fmt')
            ->resources([
                LanguageResource::class
            ]);
    }
}
