<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getShipFoldersNames()
 * @method static array getShipPath()
 * @method static array getSectionContainerNames(string $sectionName)
 * @method static string getClassFullNameFromFile(string $filePathName)
 * @method static array getSectionPaths()
 * @method static array getAllContainerNames()
 * @method static array getAllContainerPaths()
 * @method static array getSectionNames()
 * @method static array getSectionContainerPaths(string $sectionName)
 * @method static void verifyClassExist(string $className)
 *
 * @see \LashchenkoV\Porto\Foundation\Porto
 */
class Porto extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Porto';
    }
}
