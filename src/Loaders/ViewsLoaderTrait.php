<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait ViewsLoaderTrait
{
    public function loadViewsFromContainers(string $containerPath): void
    {
        $containerViewDirectory = $containerPath . '/UI/WEB/Views/';
        $containerMailTemplatesDirectory = $containerPath . '/Mails/Templates/';

        $containerName = basename($containerPath);
        $pathParts = explode(DIRECTORY_SEPARATOR, $containerPath);
        $sectionName = $pathParts[max(count($pathParts) - 2, 0)];

        $this->loadViews($containerViewDirectory, $containerName, $sectionName);
        $this->loadViews($containerMailTemplatesDirectory, $containerName, $sectionName);
    }

    public function loadViewsFromShip(): void
    {
        $portMailTemplatesDirectory = base_path(config('porto.ship.paths.views', 'app/Ship/Mails/Templates/'));
        $this->loadViews($portMailTemplatesDirectory, 'ship'); // Ship views accessible via `ship::`.
    }

    private function loadViews(string $directory, string $containerName, string $sectionName = null): void
    {
        if (File::isDirectory($directory)) {
            $this->loadViewsFrom($directory, $this->buildViewNamespace($containerName, $sectionName));
        }
    }

    private function buildViewNamespace(string $containerName, ?string $sectionName): string
    {
        return $sectionName ? (Str::camel($sectionName) . '@' . Str::camel($containerName)) : Str::camel(
            $containerName
        );
    }
}
