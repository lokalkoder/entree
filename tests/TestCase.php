<?php

namespace Lokalkoder\Entree\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lokalkoder\Entree\EntreeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Lokalkoder\\Entree\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            EntreeServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_entree_table.php.stub';
        $migration->up();
        */
    }
}
