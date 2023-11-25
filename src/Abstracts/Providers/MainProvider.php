<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Abstracts\Providers;

use Illuminate\Support\ServiceProvider as LaravelAppServiceProvider;
use LashchenkoV\Porto\Loaders\AliasesLoaderTrait;
use LashchenkoV\Porto\Loaders\ProviderLoaderTrait;

abstract class MainProvider extends LaravelAppServiceProvider
{
    use ProviderLoaderTrait;
    use AliasesLoaderTrait;

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->loadServiceProviders();
        $this->loadAliases();
    }
}
