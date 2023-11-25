<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Facades\File;
use LashchenkoV\Porto\Foundation\Facades\Porto;
use Symfony\Component\Finder\SplFileInfo;

trait ConsolesLoaderTrait
{
    public function loadConsolesFromContainers(string $containerPath): void
    {
        $containerCommandsDirectory = $containerPath . '/UI/CLI/Commands';
        $this->loadTheConsoles($containerCommandsDirectory);
    }

    public function loadConsolesFromShip(): void
    {
        $path = base_path(config('porto.ship.paths.commands', 'app/Ship/Commands'));
        $this->loadTheConsoles($path);
    }

    private function loadTheConsoles(string $directory): void
    {
        if (File::isDirectory($directory)) {
            foreach (File::allFiles($directory) as $consoleFile) {
                // Do not load route files
                if (!$this->isRouteFile($consoleFile)) {
                    // When user from the Main Service Provider, which extends Laravel
                    // service provider you get access to `$this->commands`
                    $this->commands([Porto::getClassFullNameFromFile($consoleFile->getPathname())]);
                }
            }
        }
    }

    private function isRouteFile(SplFileInfo $consoleFile): bool
    {
        return $consoleFile->getFilename() === 'Routes.php';
    }
}
