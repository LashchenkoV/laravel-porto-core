<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Commands;

use LashchenkoV\Porto\Abstracts\Commands\ConsoleCommand;
use LashchenkoV\Porto\Foundation\Porto;

class GetPortoVersionCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'porto';

    /**
     * The console command description.
     */
    protected $description = 'Display the current Porto version.';

    public function handle(): int
    {
        $this->info(Porto::VERSION);

        return 0;
    }
}
