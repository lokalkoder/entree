<?php

namespace Lokalkoder\Entree;

use Lokalkoder\Entree\Commands\CreateComponentPage;
use Lokalkoder\Entree\Commands\EntreeAdministrator;
use Lokalkoder\Entree\Commands\EntreeInstallCommand;
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
            ->hasCommands([
                EntreeInstallCommand::class,
                EntreeAdministrator::class,
                CreateComponentPage::class,
            ]);
    }
}
