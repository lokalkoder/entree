<?php

namespace Lokalkoder\Entree\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Joesama\Pintu\Services\ComponentServices;
use Symfony\Component\Console\Input\InputArgument;

class CreateComponentPage extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entree:page  {controller} {method} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make vue page base on component';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        $component = data_get($this->getComponentCollection(), $this->getControllerInput().'.'.$this->getMethodInput().'.named');

        collect($component)->each(function ($page) {
            $continue = true;

            $path = $this->getPath($page);
            $exist = $this->alreadyExists($page);

            if ((! $this->hasOption('force') || ! $this->option('force')) &&
                $exist
            ) {
                $continue = false;
            } else {
                if ($exist) {
                    $this->makeBackup($path);
                }
            }

            if ($continue) {
                $this->makeDirectory($path);

                $this->files->put($path, $this->replaceTitle($page, $this->files->get($this->getStub())));
            }
        });

        return true;
    }

    protected function getPath($name): string
    {
        return $this->laravel->resourcePath(
            'js/Pages/'.$this->qualifyClass($this->getControllerInput()).
            '/'.$this->qualifyClass($this->getMethodInput()).
            '/'.$this->qualifyClass($name).'.vue');
    }

    /**
     * Check file exist.
     *
     * @param $name
     * @return bool
     */
    protected function alreadyExists($name): bool
    {
        return $this->files->exists($this->getPath($this->qualifyClass($name)));
    }

    /**
     * Backup replication.
     *
     * @param $path
     * @return void
     */
    protected function makeBackup($path)
    {
        $basename = basename($path);

        $destination = str_replace($basename, now()->format('dmY').'/'.$basename, $path);

        $this->makeDirectory($destination);

        $this->files->copy($path, $destination);
    }

    /**
     * @param $name
     * @return string
     */
    protected function qualifyClass($name): string
    {
        $name = str_replace(['.'], [' '], $name);

        return str_replace(' ', '', Str::title($name));
    }

    /**
     * @param $title
     * @param $stub
     * @return array|string
     */
    protected function replaceTitle($title, $stub): array|string
    {
        $title = str_replace(['.'], [' '], $title);

        return str_replace(
            ['{{ title }}', '{{ name }}'],
            [Str::title($title), $this->qualifyClass($title)],
            $stub
        );
    }

    /**
     * @return string|null
     */
    protected function getStub(): ?string
    {
        return $this->resolveStubPath('/generator/js/pages/pages.stub');
    }

    /**
     * @param $stub
     * @return string|null
     */
    protected function resolveStubPath($stub): ?string
    {
        $stub = '/stubs'.$stub;

        return file_exists($stubPath = dirname(__DIR__, 2).$stub)
            ? $stubPath
            : null;
    }

    /**
     * Component collection.
     *
     * @return Collection
     */
    protected function getComponentCollection(): Collection
    {
        return (new ComponentServices('App\Providers\RouteServiceProvider'))->toCollection();
    }

    /**
     * Controller input.
     *
     * @return string
     */
    protected function getControllerInput(): string
    {
        return trim($this->argument('controller'));
    }

    /**
     * Method input.
     *
     * @return string
     */
    protected function getMethodInput(): string
    {
        return trim($this->argument('method'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['controller', InputArgument::REQUIRED, 'The Controller Name'],
            ['method', InputArgument::REQUIRED, 'The Method Name'],
        ];
    }
}
