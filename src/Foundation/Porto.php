<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Foundation;

use Illuminate\Support\Facades\File;

class Porto
{
    /**
     * The Porto version.
     */
    public const VERSION = '1.0';

    private const SHIP_NAME = 'ship';

    private const CONTAINERS_DIRECTORY_NAME = 'Containers';

    public function getShipFoldersNames(): array
    {
        $portFoldersNames = [];
        foreach ($this->getShipPath() as $portFoldersPath) {
            $portFoldersNames[] = basename($portFoldersPath);
        }

        return $portFoldersNames;
    }

    public function getShipPath(): array
    {
        return File::directories(app_path(self::SHIP_NAME));
    }

    public function getSectionContainerNames(string $sectionName): array
    {
        $containerNames = [];
        foreach (File::directories($this->getSectionPath($sectionName)) as $name) {
            $containerNames[] = basename($name);
        }

        return $containerNames;
    }

    /**
     * Get the full name (name \ namespace) of a class from its file path
     * result example: (string) "I\Am\The\Namespace\Of\This\Class"
     */
    public function getClassFullNameFromFile(string $filePathName): string
    {
        return $this->getClassNamespaceFromFile($filePathName) . '\\' . $this->getClassNameFromFile($filePathName);
    }

    public function getAllContainerNames(): array
    {
        $containerNames = [];
        foreach ($this->getAllContainerPaths() as $containersPath) {
            $containerNames[] = basename($containersPath);
        }

        return $containerNames;
    }

    public function getAllContainerPaths(): array
    {
        $sectionNames = $this->getSectionNames();
        $containerPaths = [];
        foreach ($sectionNames as $name) {
            $sectionContainerPaths = $this->getSectionContainerPaths($name);
            foreach ($sectionContainerPaths as $containerPath) {
                $containerPaths[] = $containerPath;
            }
        }
        return $containerPaths;
    }

    public function getSectionNames(): array
    {
        $sectionNames = [];
        foreach ($this->getSectionPaths() as $sectionPath) {
            $sectionNames[] = basename($sectionPath);
        }

        return $sectionNames;
    }

    public function getSectionPaths(): array
    {
        return File::directories(app_path(self::CONTAINERS_DIRECTORY_NAME));
    }

    public function getSectionContainerPaths(string $sectionName): array
    {
        return File::directories(app_path(self::CONTAINERS_DIRECTORY_NAME . DIRECTORY_SEPARATOR . $sectionName));
    }

    /**
     * Get the class namespace form file path using token
     */
    protected function getClassNamespaceFromFile(string $filePathName): ?string
    {
        $src = file_get_contents($filePathName);
        $tokens = token_get_all($src);
        $namespace = '';

        foreach ($tokens as $token) {
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                for ($i = key($tokens); $i < count($tokens); $i++) {
                    if ($tokens[$i] === ';') {
                        return trim($namespace);
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }

                break;
            }
        }

        return null;
    }

    /**
     * Get the class name from file path using token
     */
    protected function getClassNameFromFile(string $filePathName): ?string
    {
        $phpCode = file_get_contents($filePathName);
        $tokens = token_get_all($phpCode);
        $className = null;

        foreach ($tokens as $index => $token) {
            if (is_array($token) && $token[0] === T_CLASS) {
                $nextToken = $tokens[$index + 1] ?? null;
                $nextNextToken = $tokens[$index + 2] ?? null;

                if ($nextToken && $nextToken[0] === T_WHITESPACE
                    && $nextNextToken && $nextNextToken[0] === T_STRING
                ) {
                    $className = $nextNextToken[1];
                    break;
                }
            }
        }

        return $className;
    }

    private function getSectionPath(string $sectionName): string
    {
        return app_path(self::CONTAINERS_DIRECTORY_NAME . DIRECTORY_SEPARATOR . $sectionName);
    }
}
