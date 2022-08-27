<?php

namespace Lokalkoder\Entree;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lokalkoder\Entree\Commands\EntreeCommand;

class EntreeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('entree')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_entree_table')
            ->hasCommand(EntreeCommand::class);
    }
}
