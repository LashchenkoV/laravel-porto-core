<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Abstracts\Providers;

use LashchenkoV\Porto\Loaders\MiddlewaresLoaderTrait;

abstract class MiddlewareProvider extends MainProvider
{
    use MiddlewaresLoaderTrait;

    protected array $middlewares = [];

    protected array $middlewareGroups = [];

    protected array $middlewarePriority = [];

    protected array $routeMiddleware = [];

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->loadMiddlewares();
    }
}
