<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Loaders;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use LashchenkoV\Porto\Foundation\Facades\Porto;
use Symfony\Component\Finder\SplFileInfo;

trait RoutesLoaderTrait
{
    /**
     * Register all the containers routes files in the framework
     */
    public function runRoutesAutoLoader(): void
    {
        $containerPaths = Porto::getAllContainerPaths();

        foreach ($containerPaths as $containerPath) {
            $this->loadApiContainerRoutes($containerPath);
            $this->loadWebContainerRoutes($containerPath);
        }
    }

    public function getApiRouteGroup($endpointFileOrPrefixString, $controllerNamespace = null): array
    {
        $defaultMiddlewares = (array) config('porto.api.default_middlewares', ['api']);
        $defaultMiddlewares[] = $this->getRateLimitMiddleware();

        return [
            'namespace' => $controllerNamespace,
            'middleware' => array_filter($defaultMiddlewares),
            'domain' => $this->getApiUrl(),
            // If $endpointFileOrPrefixString is a file then get the version name from the file name, else if string use that string as prefix
            'prefix' => is_string(
                $endpointFileOrPrefixString
            ) ? $endpointFileOrPrefixString : $this->getApiVersionPrefix($endpointFileOrPrefixString),
        ];
    }

    /**
     * Register the Containers API routes files
     */
    private function loadApiContainerRoutes(string $containerPath): void
    {
        // Build the container api routes path
        $apiRoutesPath = $containerPath . '/UI/API/Routes';
        // Build the namespace from the path
        $controllerNamespace = $containerPath . '\\UI\API\Controllers';

        if (File::isDirectory($apiRoutesPath)) {
            $files = Arr::sort(File::allFiles($apiRoutesPath), fn (SplFileInfo $file) => $file->getFilename());
            foreach ($files as $file) {
                $this->loadApiRoute($file, $controllerNamespace);
            }
        }
    }

    private function loadApiRoute(SplFileInfo $file, string $controllerNamespace): void
    {
        $routeGroupArray = $this->getApiRouteGroup($file, $controllerNamespace);

        Route::group($routeGroupArray, fn () => $file->getPathname());
    }

    private function getRateLimitMiddleware(): ?string
    {
        if (config('porto.api.throttle.enabled')) {
            return 'throttle:' . config('porto.api.throttle.attempts') . ',' . config('porto.api.throttle.expires');
        }

        return null;
    }

    private function getApiUrl(): string
    {
        return (string) config('porto.api.url');
    }

    private function getApiVersionPrefix(SplFileInfo $file): string
    {
        return config('porto.api.prefix') . (config('porto.api.enable_version_prefix')
                ? $this->getRouteFileVersionFromFileName($file) : '');
    }

    private function getRouteFileVersionFromFileName(SplFileInfo $file): string
    {
        $fileNameWithoutExtension = $this->getRouteFileNameWithoutExtension($file);

        $fileNameWithoutExtensionExploded = explode('.', $fileNameWithoutExtension);

        end($fileNameWithoutExtensionExploded);

        $apiVersion = prev($fileNameWithoutExtensionExploded); // get the array before the last one

        // Skip versioning the API's root route
        if ($apiVersion === 'ApisRoot') {
            $apiVersion = '';
        }

        return $apiVersion;
    }

    private function getRouteFileNameWithoutExtension(SplFileInfo $file)
    {
        $fileInfo = pathinfo($file->getFileName());

        return $fileInfo['filename'];
    }

    /**
     * Register the Containers WEB routes files
     */
    private function loadWebContainerRoutes(string $containerPath): void
    {
        // build the container web routes path
        $webRoutesPath = $containerPath . '/UI/WEB/Routes';
        // build the namespace from the path
        $controllerNamespace = $containerPath . '\\UI\WEB\Controllers';

        if (File::isDirectory($webRoutesPath)) {
            $files = Arr::sort(File::allFiles($webRoutesPath), fn (SplFileInfo $file) => $file->getFilename());
            foreach ($files as $file) {
                $this->loadWebRoute($file, $controllerNamespace);
            }
        }
    }

    private function loadWebRoute(SplFileInfo $file, string $controllerNamespace): void
    {
        Route::group([
            'namespace' => $controllerNamespace,
            'middleware' => ['web'],
        ], fn () => $file->getPathname());
    }
}
