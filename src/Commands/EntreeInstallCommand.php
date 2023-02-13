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
        $this->line('Installing Laravel Breeze');

        $this->call('breeze:install', ['stack' => 'vue']);

        $this->line('Implementing Middleware');

        $this->implementMiddleware();

        $this->line('Copying Entree pages');

        $this->copyEntreePages();

        $this->line('Proccessing Vendor dependency');

        $this->call('vendor:publish', ['--provider' => 'OwenIt\Auditing\AuditingServiceProvider']);

        $this->call('vendor:publish', ['--provider' => 'Lokalkoder\Entree\EntreeServiceProvider']);

        $this->call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider']);

        $this->call('vendor:publish', ['--provider' => 'Yadahan\AuthenticationLog\AuthenticationLogServiceProvider']);

        $this->line('Proccessing NPM dependency');

        $this->processNpm();

        $this->runNpm();

        $this->components->info('Entree scaffolding installed successfully.');

        return 1;
    }

    /**
     * @return void
     */
    protected function processNpm(): void
    {
        $this->updateNodePackages(function ($packages) {
            return [
                '@fortawesome/fontawesome-free' => '^6.2.0',
                '@sweetalert2/themes' => '^5.0.12',
                'sweetalert2' => '^11.4.8',
            ] + $packages;
        });
    }

    /**
     * @return void
     */
    protected function runNpm(): void
    {
        $this->runCommands(['npm install']);
    }

    /**
     * @return void
     */
    protected function copyEntreePages(): void
    {
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Entree'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem())->copyDirectory(__DIR__.'/../../stubs/resources/js/Entree', resource_path('js/Entree'));
        (new Filesystem())->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages', resource_path('js/Pages'));

        $this->replaceInFile('Welcome', 'Entree', base_path('routes/web.php'));
    }

    /**
     * @return void
     */
    protected function implementMiddleware(): void
    {
        $this->installMiddlewareAfter('HandleInertiaRequests::class', '\App\Http\Middleware\EntreeRequests::class');

        copy(__DIR__.'/../../stubs/app/Http/Middleware/EntreeRequests.php', app_path('Http/Middleware/EntreeRequests.php'));
    }
}
