<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use LashchenkoV\Porto\Foundation\Facades\Porto;

trait AutoLoaderTrait
{
    // Using each component loader trait
    use ConfigLoaderTrait;
    use LocalizationLoaderTrait;
    use MigrationLoaderTrait;
    use ViewsLoaderTrait;
    use ProviderLoaderTrait;
    use ConsolesLoaderTrait;
    use AliasesLoaderTrait;
    use HelperLoaderTrait;

    /**
     * To be used from the `boot` function of the main service provider
     */
    public function runLoadersBoot(): void
    {
        $this->loadMigrationsFromShip();
        $this->loadLocalsFromShip();
        $this->loadViewsFromShip();
        $this->loadConsolesFromShip();
        $this->loadHelpersFromShip();

        // Iterate over all the containers folders and autoload most of the components
        foreach (Porto::getAllContainerPaths() as $containerPath) {
            $this->loadMigrationsFromContainers($containerPath);
            $this->loadLocalsFromContainers($containerPath);
            $this->loadViewsFromContainers($containerPath);
            $this->loadConsolesFromContainers($containerPath);
            $this->loadHelpersFromContainers($containerPath);
        }
    }

    public function runLoaderRegister(): void
    {
        $this->loadConfigsFromShip();
        $this->loadOnlyShipProviderFromShip();

        foreach (Porto::getAllContainerPaths() as $containerPath) {
            $this->loadConfigsFromContainers($containerPath);
            $this->loadOnlyMainProvidersFromContainers($containerPath);
        }
    }
}
