<?php

namespace Lokalkoder\Entree\Commands;

use Illuminate\Console\Command;

class EntreeCommand extends Command
{
    public $signature = 'entree';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
