<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Facades\File;

trait ConfigLoaderTrait
{
    public function loadConfigsFromShip(): void
    {
        $portConfigsDirectory = base_path(config('porto.ship.paths.configs', 'app/Ship/Configs'));
        $this->loadConfigs($portConfigsDirectory);
    }

    public function loadConfigsFromContainers(string $containerPath): void
    {
        $containerConfigsDirectory = $containerPath . '/Configs';
        $this->loadConfigs($containerConfigsDirectory);
    }

    private function loadConfigs(string $configFolder): void
    {
        if (File::isDirectory($configFolder)) {
            foreach (File::files($configFolder) as $file) {
                $name = $file->getFilenameWithoutExtension();
                $path = $configFolder . '/' . $name . '.php';

                $this->mergeConfigFrom($path, $name);
            }
        }
    }
}
