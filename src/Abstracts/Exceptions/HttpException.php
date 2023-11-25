<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Abstracts\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException as BaseHttpException;

abstract class HttpException extends BaseHttpException
{
}
