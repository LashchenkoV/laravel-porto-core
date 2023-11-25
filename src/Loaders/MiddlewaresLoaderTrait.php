<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Contracts\Http\Kernel;

trait MiddlewaresLoaderTrait
{
    public function loadMiddlewares(): void
    {
        $this->registerMiddlewares($this->middlewares);
        $this->registerMiddlewareGroups($this->middlewareGroups);
        $this->registerMiddlewarePriority($this->middlewarePriority);
        $this->registerRouteMiddleware($this->routeMiddleware);
    }

    /**
     * Registering Route Group's
     */
    private function registerMiddlewares(array $middlewares = []): void
    {
        $httpKernel = $this->app->make(Kernel::class);

        foreach ($middlewares as $middleware) {
            $httpKernel->prependMiddleware($middleware);
        }
    }

    private function registerMiddlewareGroups(array $middlewareGroups = []): void
    {
        foreach ($middlewareGroups as $key => $middleware) {
            if (!is_array($middleware)) {
                /** @psalm-suppress UndefinedInterfaceMethod */
                $this->app['router']->pushMiddlewareToGroup($key, $middleware);
            } else {
                foreach ($middleware as $item) {
                    /** @psalm-suppress UndefinedInterfaceMethod */
                    $this->app['router']->pushMiddlewareToGroup($key, $item);
                }
            }
        }
    }

    private function registerMiddlewarePriority(array $middlewarePriority = []): void
    {
        foreach ($middlewarePriority as $middleware) {
            /** @psalm-suppress UndefinedInterfaceMethod */
            if (!in_array($middleware, $this->app['router']->middlewarePriority, true)) {
                /** @psalm-suppress UndefinedInterfaceMethod */
                $this->app['router']->middlewarePriority[] = $middleware;
            }
        }
    }

    private function registerRouteMiddleware(array $routeMiddleware = []): void
    {
        foreach ($routeMiddleware as $alias => $middleware) {
            /** @psalm-suppress UndefinedInterfaceMethod */
            $this->app['router']->aliasMiddleware($alias, $middleware);
        }
    }
}
