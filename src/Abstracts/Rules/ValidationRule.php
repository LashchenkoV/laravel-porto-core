<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Abstracts\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;

abstract class ValidationRule implements ValidationRuleContract
{
    abstract public function validate(string $attribute, mixed $value, Closure $fail): void;
}
