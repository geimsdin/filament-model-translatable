<?php

namespace Unusualdope\FilamentModelTranslatable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Unusualdope\FilamentModelTranslatable\Commands\Install;

class FmtServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-model-translatable')
            ->hasMigrations('create_fmt_languages_table')
            ->hasViews()
            ->hasCommand(Install::class);

     }

    public function boot()
    {
        parent::boot();

        // Ensure views are correctly loaded
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-model-translatable');

        return $this;
    }


}
