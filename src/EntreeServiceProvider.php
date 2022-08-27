<?php

namespace Lokalkoder\Entree;

use Lokalkoder\Entree\Commands\EntreeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
