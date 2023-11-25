<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait LocalizationLoaderTrait
{
    public function loadLocalsFromContainers(string $containerPath): void
    {
        $containerLocaleDirectory = $containerPath . '/Resources/Languages';
        $containerName = basename($containerPath);
        $pathParts = explode(DIRECTORY_SEPARATOR, $containerPath);
        $sectionName = $pathParts[max(count($pathParts) - 2, 0)];

        $this->loadLocals($containerLocaleDirectory, $containerName, $sectionName);
    }

    public function loadLocalsFromShip(): void
    {
        $shipLocaleDirectory = base_path(config('porto.ship.paths.languages', 'app/Ship/Resources/Languages'));
        $this->loadLocals($shipLocaleDirectory, 'ship');
    }

    private function loadLocals(string $directory, string $containerName, string $sectionName = null): void
    {
        if (File::isDirectory($directory)) {
            $this->loadTranslationsFrom($directory, $this->buildLocaleNamespace($containerName, $sectionName));
            $this->loadJsonTranslationsFrom($directory);
        }
    }

    private function buildLocaleNamespace(string $containerName, ?string $sectionName): string
    {
        return $sectionName ? (Str::camel($sectionName) . '@' . Str::camel($containerName)) : Str::camel(
            $containerName
        );
    }
}
