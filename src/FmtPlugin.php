<?php

namespace Unusualdope\FilamentModelTranslatable;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Unusualdope\FilamentModelTranslatable\Filament\Resources\FmtLanguageResource;

class FmtPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-model-translatable';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                FmtLanguageResource::class
            ])
            ->authGuard('web');
    }

    public function boot(Panel $panel): void
    {
        app()->resolveProvider('October\Rain\Config\ServiceProvider');


    }
}
