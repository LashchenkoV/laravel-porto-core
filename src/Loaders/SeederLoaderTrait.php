<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use LashchenkoV\Porto\Foundation\Facades\Porto;

/**
 * This class is different from other loaders as it is not called by AutoLoaderTrait
 * It is called "database/seeders/DatabaseSeeder.php", Laravel main seeder and only load seeder from
 * Containers (not from "app/Ship/seeders").
 */
trait SeederLoaderTrait
{
    public function runLoadingSeeders(): void
    {
        $this->loadSeedersFromContainers();
    }

    private function loadSeedersFromContainers(): void
    {
        $containersDirectories = [];
        foreach (Porto::getSectionNames() as $sectionName) {
            foreach (Porto::getSectionContainerNames($sectionName) as $containerName) {
                $containersDirectories[] = base_path(
                    'app/Containers/' . $sectionName . '/' . $containerName . '/Data/Seeders'
                );
            }
        }

        $seedersClasses = $this->findSeedersClasses($containersDirectories, collect());
        $orderedSeederClasses = $this->sortSeeders($seedersClasses);

        $this->loadSeeders($orderedSeederClasses);
    }

    private function findSeedersClasses(array $directories, Collection $seedersClasses): Collection
    {
        foreach ($directories as $directory) {
            if (File::isDirectory($directory)) {
                foreach (File::allFiles($directory) as $seederClass) {
                    if ($seederClass->isFile()) {
                        // do not seed the classes now, just store them in a collection and w
                        $seedersClasses->push(Porto::getClassFullNameFromFile($seederClass->getPathname()));
                    }
                }
            }
        }

        return $seedersClasses;
    }

    private function sortSeeders(Collection $seedersClasses): Collection
    {
        if ($seedersClasses->isEmpty()) {
            return $seedersClasses;
        }

        $orderedSeederClasses = collect();
        foreach ($seedersClasses as $key => $seederFullClassName) {
            // if the class full namespace contain "_" it means it needs to be seeded in order
            if (str_contains($seederFullClassName, '_')) {
                // move all the seeder classes that needs to be seeded in order to their own Collection
                $orderedSeederClasses->push($seederFullClassName);
                // delete the moved classes from the original collection
                $seedersClasses->forget($key);
            }
        }

        // sort the classes that needed to be ordered
        $orderedSeederClasses = $orderedSeederClasses->sortBy(function ($seederFullClassName) {
            // get the order number form the end of each class name
            return substr($seederFullClassName, strpos($seederFullClassName, '_') + 1);
        });

        // append the randomly ordered seeder classes to the end of the ordered seeder classes
        foreach ($seedersClasses as $seederClass) {
            $orderedSeederClasses->push($seederClass);
        }

        return $orderedSeederClasses;
    }

    private function loadSeeders(Collection $seedersClasses): void
    {
        foreach ($seedersClasses as $seeder) {
            $this->call($seeder);
        }
    }
}
