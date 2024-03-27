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
            ->hasCommand(Install::class);
     }


}
