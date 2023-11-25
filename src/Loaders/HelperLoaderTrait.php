<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Facades\File;

trait HelperLoaderTrait
{
    public function loadHelpersFromContainers(string $containerPath): void
    {
        $containerHelpersDirectory = $containerPath . '/Helpers';
        $this->loadHelpers($containerHelpersDirectory);
    }

    public function loadHelpersFromShip(): void
    {
        $shipHelpersDirectory = base_path(config('porto.ship.paths.helpers', 'app/Ship/Helpers'));
        $this->loadHelpers($shipHelpersDirectory);
    }

    private function loadHelpers(string $helpersFolder): void
    {
        if (File::isDirectory($helpersFolder)) {
            foreach (File::files($helpersFolder) as $file) {
                require_once $file;
            }
        }
    }
}
