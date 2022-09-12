<?php

namespace Lokalkoder\Entree\Commands;

use Illuminate\Filesystem\Filesystem;
use Laravel\Breeze\Console\InstallCommand;
use function resource_path;

class EntreeInstallCommand extends InstallCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entree:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Entree Components';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $this->call('breeze:install', ['stack' => 'vue']);

        $this->installMiddlewareAfter('HandleInertiaRequests::class', '\App\Http\Middleware\EntreeRequests::class');

        copy(__DIR__.'/../../stubs/app/Http/Middleware/EntreeRequests.php', app_path('Http/Middleware/EntreeRequests.php'));

        (new Filesystem)->ensureDirectoryExists(resource_path('js/Entree'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Entree', resource_path('js/Entree'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages', resource_path('js/Pages'));

        $this->replaceInFile('Welcome', 'Entree', base_path('routes/web.php'));

        $this->runCommands(['npm run build']);

        $this->line('');

        $this->components->info('Entree scaffolding installed successfully.');

        return 1;
    }
}
