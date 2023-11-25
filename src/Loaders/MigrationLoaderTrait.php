<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Facades\File;

trait MigrationLoaderTrait
{
    public function loadMigrationsFromContainers(string $containerPath): void
    {
        $containerMigrationDirectory = $containerPath . '/Data/Migrations';
        $this->loadMigrations($containerMigrationDirectory);
    }

    public function loadMigrationsFromShip(): void
    {
        $migrationDirectory = base_path(config('porto.ship.paths.migrations', 'app/Ship/Migrations'));
        $this->loadMigrations($migrationDirectory);
    }

    private function loadMigrations(string $directory): void
    {
        if (File::isDirectory($directory)) {
            $this->loadMigrationsFrom($directory);
        }
    }
}
