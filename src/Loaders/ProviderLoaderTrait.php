<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use LashchenkoV\Porto\Foundation\Facades\Porto;

trait ProviderLoaderTrait
{
    /**
     * Loads only the Main Service Providers from the Containers.
     * All the Service Providers (registered inside the main), will be
     * loaded from the `boot()` function on the parent of the Main
     * Service Providers.
     */
    public function loadOnlyMainProvidersFromContainers(string $containerPath): void
    {
        $containerProvidersDirectory = $containerPath . '/Providers';
        $this->loadProviders($containerProvidersDirectory);
    }

    /**
     * Load the all the registered Service Providers on the Main Service Provider.
     */
    public function loadServiceProviders(): void
    {
        // `$this->serviceProviders` is declared on each Container's Main Service Provider
        foreach ($this->serviceProviders ?? [] as $provider) {
            if (class_exists($provider)) {
                $this->loadProvider($provider);
            }
        }
    }

    public function loadOnlyShipProviderFromShip(): void
    {
        $this->loadProvider('App\Ship\Providers\ShipProvider');
    }

    private function loadProviders(string $directory): void
    {
        $mainServiceProviderNameStartWith = 'Main';

        if (File::isDirectory($directory)) {
            foreach (File::allFiles($directory) as $file) {
                if ($file->isFile()) {
                    // Check if this is the Main Service Provider
                    if (Str::startsWith($file->getFilename(), $mainServiceProviderNameStartWith)) {
                        $this->loadProvider(Porto::getClassFullNameFromFile($file->getPathname()));
                    }
                }
            }
        }
    }

    private function loadProvider(ServiceProvider|string $providerFullName): void
    {
        App::register($providerFullName);
    }
}
