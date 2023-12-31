<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Abstracts\Exceptions\Handlers;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = ['password'];
}
