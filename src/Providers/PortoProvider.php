<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use LashchenkoV\Porto\Abstracts\Providers\EventsProvider;
use LashchenkoV\Porto\Abstracts\Providers\MainProvider as AbstractMainProvider;
use LashchenkoV\Porto\Foundation\Porto;
use LashchenkoV\Porto\Loaders\AutoLoaderTrait;

class PortoProvider extends AbstractMainProvider
{
    use AutoLoaderTrait;

    public function boot(): void
    {
        parent::boot();

        // Autoload most of the Containers and Ship Components
        $this->runLoadersBoot();

        // Solves the "specified key was too long" error, introduced in L5.4
        Schema::defaultStringLength(191);
    }

    public function register(): void
    {
        // NOTE: function order of this calls bellow are important. Do not change it.

        $this->app->bind('Porto', Porto::class);
        // Register Core Facade Classes, should not be registered in the $aliases property, since they are used
        // by the auto-loading scripts, before the $aliases property is executed.
        $this->app->alias(Porto::class, 'Porto');

        // parent::register() should be called AFTER we bind 'Porto'
        parent::register();

        $this->runLoaderRegister();

        $this->overrideLaravelBaseProviders();
    }

    /**
     * Register Overided Base providers
     * @see \Illuminate\Foundation\Application::registerBaseServiceProviders
     */
    private function overrideLaravelBaseProviders(): void
    {
        App::register(EventsProvider::class);
    }
}
